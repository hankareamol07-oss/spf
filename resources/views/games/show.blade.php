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
        <header class="nipun-toolbar p-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;">
                <a class="nq-tool-btn" href="{{ route('games.index') }}">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i><span>खेळांची यादी</span>
                </a>
                <div style="min-width: 0;">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="nipun-class-badge">इयत्ता ४ थी</span>
                        <h1 class="h4 mb-0">{{ $gameSession->game->title_marathi }}</h1>
                    </div>
                    <div class="nq-sub">{{ data_get($gameSession->game->configuration, 'competency', 'अध्ययन निष्पत्ती प्रगती मापन') }}</div>
                </div>
            </div>

            <button class="nq-tool-btn" type="button" data-game-sound aria-pressed="true">
                <i class="bi bi-volume-up" aria-hidden="true"></i>
                <span>आवाज सुरू</span>
            </button>
        </header>

        <!-- Main Interactive Game Stage -->
        <section class="nipun-stage" aria-label="खेळ" aria-live="polite">
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

            <div class="nipun-progress-wrap" role="progressbar" aria-label="खेळाची प्रगती" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="nipun-progress-fill" data-game-progress-bar></div>
            </div>

            <div class="nipun-prompt-box">
                <p class="nipun-game-instruction" data-game-instruction>सूचना वाचत आहे...</p>

                <button class="nipun-listen-btn" type="button" data-game-listen hidden>
                    <i class="bi bi-volume-up-fill fs-5" aria-hidden="true"></i>
                    <span>मोठ्याने ऐका</span>
                </button>

                <h2 class="nipun-main-prompt" data-game-prompt tabindex="-1">खेळ लोड होत आहे...</h2>
            </div>

            <div data-game-visual-area></div>

            <div class="nipun-choices-grid" data-game-choices></div>

            <div class="nipun-feedback-banner" data-game-feedback-banner aria-live="assertive">
                <div class="nipun-feedback-title" data-game-feedback-title>शाब्बास!</div>
                <div class="nipun-feedback-msg" data-game-feedback-msg>अचूक उत्तर!</div>
            </div>

            <div class="text-center py-4" data-game-loading hidden>
                <div class="spinner-border" style="color: var(--nq-orange);" role="status">
                    <span class="visually-hidden">उत्तर तपासत आहे...</span>
                </div>
            </div>
            <button type="button" class="nq-btn nq-btn-orange mx-auto mt-3" data-game-finish-retry hidden>निकाल जतन करण्याचा पुन्हा प्रयत्न करा</button>
        </section>

        <noscript>
            <div class="alert alert-warning mt-3">हा परस्परसंवादी खेळ खेळण्यासाठी JavaScript सुरू असणे आवश्यक आहे.</div>
        </noscript>
    </div>

    <!-- State JSON Payload -->
    <script type="application/json" id="game-state-{{ $gameSession->id }}">@json($initialState)</script>

    <!-- Custom Class 4 Game Engine Script -->
    <script src="{{ asset('js/nipun-games.js') }}"></script>
</x-layouts.app>
