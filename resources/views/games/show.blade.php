<x-layouts.app :title="$gameSession->game->title_marathi . ' (इयत्ता ४ थी)'">
    <link rel="stylesheet" href="{{ asset('css/nipun-games.css') }}">

    <div
        class="nipun-game-shell my-3"
        data-nipun-game-root
        data-game-code="{{ $gameSession->game->code }}"
        data-state-element="game-state-{{ $gameSession->id }}"
        data-answer-url="{{ route('games.sessions.answer', [$gameSession, '__QUESTION__']) }}"
        data-finish-url="{{ route('games.sessions.finish', $gameSession) }}"
        data-result-url="{{ route('games.sessions.result', $gameSession) }}"
    >
        <!-- Toolbar -->
        <header class="nipun-toolbar p-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <a class="btn btn-outline-secondary btn-sm rounded-pill" href="{{ route('games.index') }}">
                    <i class="bi bi-arrow-left me-1"></i>खेळांची यादी
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="nipun-class-badge">इयत्ता ४ थी</span>
                        <h1 class="h4 mb-0 fw-bold text-dark">{{ $gameSession->game->title_marathi }}</h1>
                    </div>
                    <div class="small text-secondary">{{ data_get($gameSession->game->configuration, 'competency', 'अध्ययन निष्पत्ती प्रगती मापन') }}</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="button" data-game-sound aria-pressed="true">
                    <i class="bi bi-volume-up" aria-hidden="true"></i>
                    <span>आवाज सुरू</span>
                </button>
            </div>
        </header>

        <!-- Main Interactive Game Stage -->
        <main class="nipun-stage" aria-live="polite">
            <!-- HUD Stats -->
            <div class="nipun-stats-hud">
                <div class="nipun-stat-card">
                    <span class="nipun-stat-label">गुण (Score)</span>
                    <strong class="nipun-stat-val" data-game-score>०</strong>
                </div>
                <div class="nipun-stat-card">
                    <span class="nipun-stat-label">संधी (Lives)</span>
                    <strong class="nipun-lives-val" data-game-lives>♥ ♥ ♥</strong>
                </div>
                <div class="nipun-stat-card">
                    <span class="nipun-stat-label">वेळ (Timer)</span>
                    <strong class="nipun-stat-val" data-game-timer>--</strong>
                </div>
                <div class="nipun-stat-card">
                    <span class="nipun-stat-label">प्रगती (Progress)</span>
                    <strong class="nipun-stat-val" data-game-progress>० / ०</strong>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="nipun-progress-wrap" role="progressbar" aria-label="खेळाची प्रगती" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="nipun-progress-fill" data-game-progress-bar></div>
            </div>

            <!-- Question Prompt Area -->
            <div class="nipun-prompt-box">
                <p class="nipun-game-instruction" data-game-instruction>सूचना वाचत आहे...</p>

                <button class="nipun-listen-btn" type="button" data-game-listen hidden>
                    <i class="bi bi-volume-up-fill fs-5" aria-hidden="true"></i>
                    <span>मोठ्याने ऐका</span>
                </button>

                <h2 class="nipun-main-prompt" data-game-prompt>खेळ लोड होत आहे...</h2>
            </div>

            <!-- Interactive Visual Engine Area (Apple tree / Place value house / Lilypad pond) -->
            <div data-game-visual-area></div>

            <!-- Choices / Options Grid -->
            <div class="nipun-choices-grid" data-game-choices></div>

            <!-- Celebratory / Encouraging Feedback Toast -->
            <div class="nipun-feedback-banner" data-game-feedback-banner aria-live="assertive">
                <div class="nipun-feedback-title" data-game-feedback-title>शाब्बास! 🌟</div>
                <div class="nipun-feedback-msg" data-game-feedback-msg>अचूक उत्तर!</div>
            </div>

            <!-- Loading Spinner -->
            <div class="text-center py-4" data-game-loading hidden>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">उत्तर तपासत आहे...</span>
                </div>
            </div>
        </main>

        <noscript>
            <div class="alert alert-warning mt-3">हा परस्परसंवादी खेळ खेळण्यासाठी JavaScript सुरू असणे आवश्यक आहे.</div>
        </noscript>
    </div>

    <!-- State JSON Payload -->
    <script type="application/json" id="game-state-{{ $gameSession->id }}">@json($initialState)</script>

    <!-- Custom Class 4 Game Engine Script -->
    <script src="{{ asset('js/nipun-games.js') }}"></script>
</x-layouts.app>
