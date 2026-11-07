<?php

namespace Tests\Support;

use App\Models\AcademicYear;
use App\Models\Game;
use App\Models\GameLevel;
use App\Models\GameSession;
use App\Models\School;
use App\Models\Skill;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

abstract class IsolatedGameTestCase extends TestCase
{
    private string $databaseFile;

    private string $bootstrapDirectory;

    protected Student $student;

    protected AcademicYear $year;

    protected Skill $skill;

    protected Game $game;

    protected GameLevel $level;

    public function createApplication(): Application
    {
        $file = tempnam(sys_get_temp_dir(), 'spf-game-tests-');
        if ($file === false) {
            throw new RuntimeException('Cannot create the isolated test database.');
        }
        $this->databaseFile = $file;
        $this->bootstrapDirectory = $file.'-bootstrap';
        if (! mkdir($this->bootstrapDirectory.'/cache', 0700, true)) {
            throw new RuntimeException('Cannot create the isolated Laravel bootstrap directory.');
        }

        // Boot Laravel, but never load the portal's .env, cached config, routes,
        // providers, seeders, sessions or external database connections.
        $app = Application::configure(basePath: dirname(__DIR__, 2))
            ->withProviders([], withBootstrapProviders: false)
            ->create();
        $app->loadEnvironmentFrom('.env.testing-isolated');
        $app->useBootstrapPath($this->bootstrapDirectory);
        $app->afterBootstrapping(LoadConfiguration::class, function (Application $app): void {
            $app['config']->set([
                'app.env' => 'testing',
                'app.timezone' => 'UTC',
                'database.default' => 'isolated_game_tests',
                'database.connections' => [
                    'isolated_game_tests' => [
                        'driver' => 'sqlite',
                        'database' => $this->databaseFile,
                        'prefix' => '',
                        'foreign_key_constraints' => true,
                    ],
                ],
                'cache.default' => 'array',
                'session.driver' => 'array',
                'queue.default' => 'sync',
                'mail.default' => 'array',
                'logging.default' => 'null',
                'gamification.xp.completion.game_session' => 20,
                'gamification.xp.accuracy_bonus' => 20,
                'gamification.xp.accuracy_bonus_threshold' => 80,
                'gamification.xp.personal_best' => 25,
                'gamification.xp.daily_goal' => 30,
                'gamification.daily_goal.activities' => 100,
                'gamification.daily_goal.minutes' => 1000,
                'gamification.daily_goal.xp' => 100000,
                'gamification.streak.initial_freezes' => 0,
            ]);
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-11-07 10:00:00', 'UTC'));
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame($this->databaseFile, DB::connection()->getDatabaseName());
        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);

        $school = School::query()->create([
            'code' => 'fictional-school', 'name' => 'Fictional Test School', 'timezone' => 'UTC',
        ]);
        $user = User::query()->create([
            'name' => 'Fictional Student', 'email' => 'student@example.invalid',
            'password' => 'test-only-not-a-real-account', 'school_id' => $school->id,
        ]);
        $this->student = Student::query()->create([
            'user_id' => $user->id, 'school_id' => $school->id, 'student_number' => 'TEST-001',
        ]);
        $this->year = AcademicYear::query()->create([
            'school_id' => $school->id, 'name' => '2026-2027',
            'starts_on' => '2026-06-01', 'ends_on' => '2027-05-31',
        ]);
        $subject = Subject::query()->create([
            'code' => 'test-maths', 'name' => 'Test Maths', 'name_marathi' => 'गणित',
        ]);
        $this->skill = Skill::query()->create([
            'subject_id' => $subject->id, 'code' => 'test-addition',
            'name' => 'Test Addition', 'name_marathi' => 'बेरीज',
        ]);
        $this->game = Game::query()->create([
            'code' => 'test-game', 'engine_key' => 'choice', 'title' => 'Test Game',
            'title_marathi' => 'चाचणी खेळ', 'status' => 'published',
        ]);
        $this->game->skills()->attach($this->skill);
        $this->level = GameLevel::query()->create([
            'game_id' => $this->game->id, 'level' => 1, 'name' => 'Test Level',
            'configuration' => [], 'difficulty' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        try {
            parent::tearDown();
        } finally {
            Carbon::setTestNow();
            if (isset($this->databaseFile) && is_file($this->databaseFile)) {
                unlink($this->databaseFile);
            }
            if (isset($this->bootstrapDirectory)) {
                (new Filesystem)->deleteDirectory($this->bootstrapDirectory);
            }
        }
    }

    protected function gameSession(int $questionCount = 1, int $lives = 3): GameSession
    {
        $session = GameSession::query()->create([
            'session_key' => (string) Str::uuid(),
            'game_id' => $this->game->id, 'game_level_id' => $this->level->id,
            'student_id' => $this->student->id, 'academic_year_id' => $this->year->id,
            'status' => 'in_progress', 'difficulty' => 1, 'started_at' => now(),
            'expires_at' => now()->addMinutes(5),
            'server_state' => [
                'lives_total' => $lives, 'lives_remaining' => $lives, 'score' => 0,
                'answered_count' => 0, 'correct_count' => 0, 'incorrect_count' => 0,
                'question_started_at' => now()->toIso8601String(),
            ],
        ]);
        for ($sequence = 1; $sequence <= $questionCount; $sequence++) {
            $session->questions()->create([
                'skill_id' => $this->skill->id, 'sequence' => $sequence,
                'type' => 'choice', 'prompt' => '1 + 1?', 'prompt_marathi' => '१ + १?',
                'choices' => [['value' => '2', 'label' => '2'], ['value' => '3', 'label' => '3']],
                'expected_answer' => ['value' => '2'],
                'difficulty' => 1, 'max_score' => 100,
            ]);
        }

        return $session;
    }

    /**
     * There is deliberately no RefreshDatabase/DatabaseTransactions wrapper:
     * the service must COMMIT before the transport failure, not roll back with it.
     */
    protected function loseResponseAfterCommit(Closure $request): void
    {
        $this->assertSame(0, DB::transactionLevel());
        try {
            $request();
            $this->assertSame(0, DB::transactionLevel());
            throw new RuntimeException('Simulated response lost after server commit');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated response lost after server commit', $exception->getMessage());
        }
        // A new connection must see the committed write, not stale Eloquent state.
        DB::purge();
        DB::reconnect();
        $this->assertSame(0, DB::transactionLevel());
    }

    protected function savedEffects(): array
    {
        $effects = [];
        foreach ([
            'game_answers', 'game_results', 'xp_transactions', 'daily_goals',
            'streaks', 'achievements', 'student_badges', 'student_skill_events',
            'student_skill_progress', 'game_sessions',
        ] as $table) {
            $effects[$table] = DB::table($table)->orderBy('id')->get()->toArray();
        }

        return $effects;
    }
}
