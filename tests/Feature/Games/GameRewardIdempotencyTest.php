<?php

namespace Tests\Feature\Games;

use App\Http\Controllers\StudentGameController;
use App\Http\Requests\Game\SubmitGameAnswerRequest;
use App\Models\Badge;
use App\Models\GameAnswer;
use App\Models\GameQuestion;
use App\Models\GameResult;
use App\Models\GameSession;
use App\Models\XpTransaction;
use App\Services\GameSessionService;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Support\IsolatedGameTestCase;

class GameRewardIdempotencyTest extends IsolatedGameTestCase
{
    public static function answerValues(): array
    {
        return ['correct' => ['2', '3', 3, 100], 'incorrect' => ['3', '2', 2, 0]];
    }

    #[DataProvider('answerValues')]
    public function test_answer_retry_preserves_original_answer_score_lives_and_next_question(
        string $original,
        string $changedRetry,
        int $lives,
        int $score,
    ): void {
        $session = $this->gameSession(2);
        $question = $session->questions()->orderBy('sequence')->firstOrFail();
        $this->loseResponseAfterCommit(fn () => $this->app->make(GameSessionService::class)
            ->answer($session, $question, ['value' => $original]));

        $savedAnswer = GameAnswer::query()->sole()->getAttributes();
        $effects = $this->savedEffects();
        $this->travel(10)->seconds();
        $replay = $this->app->make(GameSessionService::class)->answer(
            $session->fresh(), $question->fresh(), ['value' => $changedRetry],
        );

        $this->assertTrue($replay['is_replay']);
        $this->assertFalse($replay['completed']);
        $this->assertSame(1, $replay['stats']['answered_count']);
        $this->assertSame($lives, $replay['stats']['lives_remaining']);
        $this->assertSame($score, $replay['stats']['score']);
        $this->assertSame(2, $replay['question']['sequence']);
        $this->assertSame($savedAnswer, GameAnswer::query()->sole()->getAttributes());
        $this->assertEquals($effects, $this->savedEffects());
        $this->assertDatabaseCount('game_results', 0);
        $this->assertDatabaseCount('xp_transactions', 0);
    }

    public function test_final_answer_response_can_be_lost_and_replayed_through_controller(): void
    {
        $session = $this->gameSession();
        $question = $session->questions()->sole();
        $this->loseResponseAfterCommit(fn () => $this->answerResponse($session, $question));
        $this->assertCompletedOnce($session, 1, 40);
        $effects = $this->savedEffects();

        $this->travel(10)->seconds();
        for ($retry = 0; $retry < 3; $retry++) {
            $response = $this->answerResponse($session->fresh(), $question->fresh())->getData(true);
            $this->assertTrue($response['is_replay']);
            $this->assertTrue($response['completed']);
            $this->assertSame(40, $response['result']['xp_awarded']);
            $this->assertSame(100, $response['result']['score']);
            $this->assertSame(1, $response['stats']['answered_count']);
        }
        $finish = $this->app->make(StudentGameController::class)->finish(
            $session->fresh(), $this->app->make(GameSessionService::class),
        )->getData(true);
        $this->assertTrue($finish['is_replay']);
        $this->assertEquals($effects, $this->savedEffects());
        $this->assertCompletedOnce($session, 1, 40);
    }

    public function test_expired_completion_response_can_be_lost_and_replayed_through_controller(): void
    {
        $session = $this->gameSession(2);
        $this->app->make(GameSessionService::class)->answer(
            $session, $session->questions()->orderBy('sequence')->firstOrFail(), ['value' => '2'],
        );
        $this->travel(6)->minutes();
        $finish = fn () => $this->app->make(StudentGameController::class)->finish(
            $session->fresh(), $this->app->make(GameSessionService::class),
        );
        $this->loseResponseAfterCommit($finish);
        $this->assertCompletedOnce($session, 1, 20);
        $effects = $this->savedEffects();
        $replay = $finish()->getData(true);

        $this->assertTrue($replay['is_replay']);
        $this->assertTrue($replay['completed']);
        $this->assertSame(50.0, (float) $replay['result']['accuracy']);
        $this->assertSame(20, $replay['result']['xp_awarded']);
        // A delayed answer after completion cannot save the unanswered question.
        $late = $this->app->make(GameSessionService::class)->answer(
            $session->fresh(), $session->questions()->orderByDesc('sequence')->firstOrFail(), ['value' => '2'],
        );
        $this->assertTrue($late['is_replay']);
        $this->assertEquals($effects, $this->savedEffects());
        $this->assertCompletedOnce($session, 1, 20);
    }

    public function test_losing_last_life_completes_only_once_on_retry(): void
    {
        $session = $this->gameSession(2, lives: 1);
        $question = $session->questions()->orderBy('sequence')->firstOrFail();
        $this->loseResponseAfterCommit(fn () => $this->app->make(GameSessionService::class)
            ->answer($session, $question, ['value' => '3']));
        $effects = $this->savedEffects();
        $replay = $this->app->make(GameSessionService::class)->answer(
            $session->fresh(), $question->fresh(), ['value' => '2'],
        );
        $this->assertTrue($replay['is_replay']);
        $this->assertTrue($replay['completed']);
        $this->assertSame(0, $replay['stats']['lives_remaining']);
        $this->assertSame(0, $replay['result']['score']);
        $this->assertEquals($effects, $this->savedEffects());
        $this->assertCompletedOnce($session, 1, 20);
    }

    public function test_expired_unanswered_game_never_grants_xp_even_after_retries(): void
    {
        $session = $this->gameSession();
        $this->travel(6)->minutes();
        $this->loseResponseAfterCommit(fn () => $this->app->make(GameSessionService::class)
            ->finishExpired($session));
        $effects = $this->savedEffects();
        $replay = $this->app->make(GameSessionService::class)->finishExpired($session->fresh());
        $this->assertTrue($replay['is_replay']);
        $this->assertSame(0, $replay['result']['xp_awarded']);
        $this->assertDatabaseCount('game_results', 1);
        $this->assertDatabaseCount('game_answers', 0);
        $this->assertDatabaseCount('xp_transactions', 0);
        $this->assertEquals($effects, $this->savedEffects());
    }

    public function test_gamification_replay_does_not_repeat_completion_or_any_bonus_side_effect(): void
    {
        config(['gamification.daily_goal.activities' => 1]);
        Badge::query()->create([
            'code' => 'test-first-game', 'name' => 'Test Badge', 'name_marathi' => 'चाचणी',
            'criteria' => ['game_count' => 1], 'xp_bonus' => 7, 'is_active' => true,
        ]);
        $session = $this->gameSession();
        $record = fn () => $this->app->make(GamificationService::class)->recordCompletion(
            $this->student->fresh(), $this->year->fresh(), 'game_session', $session->id,
            $this->skill->id, 100, 90, isPersonalBest: true,
        );
        $this->loseResponseAfterCommit(function () use ($record): void {
            $first = $record();
            $this->assertFalse($first['is_replay']);
            $this->assertSame(102, $first['xp_awarded']); // 20 + 20 + 25 + 7 + 30
        });
        $effects = $this->savedEffects();
        $this->travel(10)->seconds();
        $replay = $record();

        $this->assertTrue($replay['is_replay']);
        $this->assertSame(0, $replay['xp_awarded']);
        $this->assertSame([], $replay['badges']);
        $this->assertEquals($effects, $this->savedEffects());
        $this->assertSame(102, (int) XpTransaction::query()->sum('points'));
        foreach (['game_completed', 'accuracy_bonus', 'personal_best', 'badge_bonus', 'daily_goal_completed'] as $reason) {
            $this->assertSame(1, XpTransaction::query()->where('reason', $reason)->count(), $reason);
        }
        $this->assertSame(1, $replay['daily_goal']->completed_activities);
        $this->assertSame(2, $replay['daily_goal']->completed_minutes);
        $this->assertSame(1, $replay['streak']->current_days);
        $this->assertDatabaseCount('student_badges', 1);
    }

    public function test_distinct_completed_sessions_each_receive_their_own_reward(): void
    {
        foreach ([1, 2] as $count) {
            $session = $this->gameSession();
            $question = $session->questions()->sole();
            $this->app->make(GameSessionService::class)->answer($session, $question, ['value' => '2']);
            $this->app->make(GameSessionService::class)->answer($session->fresh(), $question->fresh(), ['value' => '2']);
            $this->assertSame(1, $session->result()->count());
            $this->assertSame(1, $this->completionGrants($session));
            $this->assertDatabaseCount('game_answers', $count);
            $this->assertDatabaseCount('game_results', $count);
            $this->assertSame(40 * $count, (int) XpTransaction::query()->sum('points'));
        }
        $this->assertSame(2, (int) DB::table('daily_goals')->sole()->completed_activities);
        $this->assertSame(2, (int) DB::table('student_skill_progress')->sole()->game_count);
    }

    private function answerResponse(GameSession $session, GameQuestion $question): JsonResponse
    {
        // Exercise the actual JSON controller response, not routing/authentication.
        $request = new SubmitGameAnswerRequest;
        $route = new Route('POST', 'isolated-games/{game_session}/{game_question}', fn () => null);
        $route->bind($request);
        $route->setParameter('game_session', $session);
        $route->setParameter('game_question', $question);
        $request->setRouteResolver(fn () => $route);
        $request->setValidator(Validator::make(
            ['answer' => ['value' => '2']],
            $request->rules(),
        ));

        return $this->app->make(StudentGameController::class)->answer(
            $request, $session, $question, $this->app->make(GameSessionService::class),
        );
    }

    private function completionGrants(GameSession $session): int
    {
        return XpTransaction::query()->where('student_id', $this->student->id)
            ->where('academic_year_id', $this->year->id)->where('source_type', 'game_session')
            ->where('source_id', $session->id)->where('reason', 'game_completed')->count();
    }

    private function assertCompletedOnce(GameSession $session, int $answers, int $xp): void
    {
        $this->assertSame('completed', $session->fresh()->status);
        $this->assertDatabaseCount('game_answers', $answers);
        $this->assertDatabaseCount('game_results', 1);
        $this->assertSame(1, $this->completionGrants($session));
        $this->assertSame($xp, (int) XpTransaction::query()->sum('points'));
        $this->assertSame($xp, GameResult::query()->sole()->xp_awarded);
        $this->assertSame(1, (int) DB::table('daily_goals')->sole()->completed_activities);
        $this->assertSame(1, (int) DB::table('student_skill_progress')->sole()->game_count);
    }
}
