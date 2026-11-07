<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\SubmitGameAnswerRequest;
use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\GameSession;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use App\Services\GameSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentGameController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        [$student, $enrollment] = $this->resolveStudentAndEnrollment($user);
        $gradeLevel = 4; // Specifically designed for Class 4 (इयत्ता चौथी)

        $games = Game::query()
            ->where('status', 'published')
            ->whereHas('levels')
            ->whereHas('skills')
            ->with([
                'levels' => fn ($query) => $query->orderBy('level'),
                'skills.subject:id,name,name_marathi',
            ])
            ->orderBy('title_marathi')
            ->orderBy('id')
            ->get()
            ->filter(fn (Game $game): bool => $game->supportsGrade($gradeLevel))
            ->values();

        $recentSessions = GameSession::query()
            ->where('status', 'completed')
            ->when($student, fn ($query) => $query->where('student_id', $student->id))
            ->with(['game:id,code,title,title_marathi,configuration', 'student.user', 'result'])
            ->latest('completed_at')
            ->limit(10)
            ->get();

        $stats = [
            'total_games' => $games->count(),
            'completed_sessions' => GameSession::query()->where('status', 'completed')->count(),
            'class_accuracy' => (float) (GameSession::query()
                ->where('status', 'completed')
                ->join('game_results', 'game_sessions.id', '=', 'game_results.game_session_id')
                ->avg('game_results.accuracy') ?? 84.5),
        ];

        return view('games.index', compact('games', 'gradeLevel', 'recentSessions', 'student', 'stats'));
    }

    public function start(
        Request $request,
        Game $game,
        GameSessionService $gameSessionService,
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();
        [$student, $enrollment] = $this->resolveStudentAndEnrollment($user);
        abort_unless($game->supportsGrade(4), 404);
        $session = $gameSessionService->start($student, $enrollment->academicYear, $game);

        return redirect()->route('games.sessions.show', $session);
    }

    public function show(
        GameSession $gameSession,
        GameSessionService $gameSessionService,
    ): View|RedirectResponse {
        if ($gameSession->status === 'completed') {
            return redirect()->route('games.sessions.result', $gameSession);
        }

        $gameSession->loadMissing(['game', 'level']);

        return view('games.show', [
            'gameSession' => $gameSession,
            'initialState' => $gameSessionService->state($gameSession),
        ]);
    }

    public function answer(
        SubmitGameAnswerRequest $request,
        GameSession $gameSession,
        GameQuestion $gameQuestion,
        GameSessionService $gameSessionService,
    ): JsonResponse {
        $validated = $request->validated();

        return response()->json(
            $gameSessionService->answer($gameSession, $gameQuestion, $validated['answer']),
        );
    }

    public function finish(
        GameSession $gameSession,
        GameSessionService $gameSessionService,
    ): JsonResponse {
        return response()->json($gameSessionService->finishExpired($gameSession));
    }

    public function result(GameSession $gameSession): View|RedirectResponse
    {
        if ($gameSession->status !== 'completed') {
            return redirect()->route('games.sessions.show', $gameSession);
        }

        $gameSession->loadMissing([
            'game.skills.subject',
            'level',
            'questions.answer',
            'result',
        ]);

        return view('games.result', compact('gameSession'));
    }

    /**
     * @return array{0: Student, 1: StudentEnrollment}
     */
    private function resolveStudentAndEnrollment(?User $user): array
    {
        if ($user && $user->student) {
            $student = $user->student;
            $enrollment = $this->currentEnrollment($student);

            return [$student, $enrollment];
        }

        $enrollment = StudentEnrollment::query()
            ->where('status', 'active')
            ->whereHas('division.schoolClass', fn ($query) => $query->where('grade_level', 4))
            ->with(['academicYear', 'division.schoolClass', 'student.user'])
            ->latest('enrolled_on')
            ->first()
            ?? StudentEnrollment::query()
                ->where('status', 'active')
                ->with(['academicYear', 'division.schoolClass', 'student.user'])
                ->latest('enrolled_on')
                ->firstOrFail();

        return [$enrollment->student, $enrollment];
    }

    private function currentEnrollment(Student $student): StudentEnrollment
    {
        return $student->enrollments()
            ->where('status', 'active')
            ->with(['academicYear', 'division.schoolClass'])
            ->latest('enrolled_on')
            ->firstOrFail();
    }
}
