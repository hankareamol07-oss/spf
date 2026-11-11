<x-layouts.app :title="$gameSession->game->title_marathi . ' - प्रगती निकाल (इयत्ता ४ थी)'">
    <link rel="stylesheet" href="{{ asset('css/nipun-games.css') }}">
    @php
        $result = $gameSession->result;
        $acc = (float) ($result->accuracy ?? 0);
        $isMastered = $acc >= 80;
        $isDeveloping = $acc >= 50 && $acc < 80;
        $levelTitle = $isMastered ? 'निपुण प्रगत स्तर (Mastered)' : ($isDeveloping ? 'प्रगतीपथावर (Developing)' : 'सराव आवश्यक (Needs Practice)');
        $levelClass = $isMastered ? 'nq-level-hi' : ($isDeveloping ? 'nq-level-mid' : 'nq-level-lo');
        $starCount = $isMastered ? 3 : ($isDeveloping ? 2 : 1);
        $competency = data_get($gameSession->game->configuration, 'competency', 'इयत्ता ४ थी अध्ययन निष्पत्ती');
    @endphp

    <div class="nq-page">
        <section class="nq-hero nq-result-hero">
            <span class="nq-eyebrow">
                <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>
                <span>इयत्ता ४ थी शैक्षणिक प्रगती मापन निकाल</span>
            </span>

            <div class="d-block">
                <div class="nq-stars" role="img" aria-label="{{ $starCount }} / 3 तारे">
                    @for ($i = 1; $i <= 3; $i++)
                        <i class="bi bi-star-fill {{ $i <= $starCount ? 'on' : 'off' }}" aria-hidden="true"></i>
                    @endfor
                </div>
            </div>

            <h1 class="nq-hero-title mt-1 mb-1">{{ $gameSession->game->title_marathi }}</h1>
            <div style="color: rgba(255,248,242,.65);">{{ $gameSession->game->title }}</div>

            <span class="nq-level {{ $levelClass }}">{{ $levelTitle }}</span>

            <div class="small" style="color: rgba(255,248,242,.75);">
                <i class="bi bi-check-circle-fill me-1" style="color: #7FE0D6;" aria-hidden="true"></i>मूल्यांकित कौशल्य: <strong>{{ $competency }}</strong>
            </div>

            <div class="nq-stats mx-auto" style="max-width: 52rem;">
                <div class="nq-stat">
                    <small>प्राप्त गुण</small>
                    <strong>{{ $result?->score ?? 0 }}</strong>
                    <small>कमाल: {{ $result?->max_score ?? 0 }}</small>
                </div>
                <div class="nq-stat">
                    <small>अचूकता (Accuracy)</small>
                    <strong>{{ number_format($acc, 0) }}%</strong>
                    <small>लक्ष्य: ८०%+</small>
                </div>
                <div class="nq-stat">
                    <small>अचूक उत्तरे</small>
                    <strong>{{ $result?->correct_count ?? 0 }} / {{ $gameSession->questions->count() }}</strong>
                    <small>प्रश्न अचूक</small>
                </div>
                <div class="nq-stat">
                    <small>कमावलेले XP</small>
                    <strong>+{{ $result?->xp_awarded ?? 0 }}</strong>
                    <small>गुणवत्ता बोनस</small>
                </div>
            </div>

            <div class="nq-result-actions">
                <form method="POST" action="{{ route('games.start', $gameSession->game) }}">
                    @csrf
                    <button class="nq-btn nq-btn-orange" type="submit">
                        <i class="bi bi-arrow-repeat" aria-hidden="true"></i>पुन्हा खेळा
                    </button>
                </form>
                <a class="nq-btn nq-btn-light" href="{{ route('games.index') }}">
                    <i class="bi bi-grid-fill" aria-hidden="true"></i>इयत्ता ४ थी चे इतर खेळ
                </a>
                <a class="nq-btn nq-btn-light" href="{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? route('dashboard').'#learning-map' : route('fellowship.reports.index') }}">
                    <i class="bi bi-map" aria-hidden="true"></i>{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? 'माझा अध्ययन नकाशा' : 'फेलोशिप अहवाल' }}
                </a>
            </div>
        </section>

        <section class="nq-panel mt-0">
            <h2 class="h5 mb-1">प्रश्नोत्तर विश्लेषण व आढावा</h2>
            <p class="small mb-3" style="color: var(--nq-ink-soft);">प्रत्येक प्रश्नाचे नोंदवलेले उत्तर आणि अचूक पडताळणी</p>

            @foreach ($gameSession->questions as $index => $question)
                @php
                    $submittedValue = data_get($question->answer?->answer, 'value');
                    $expectedValue = data_get($question->expected_answer, 'value');
                    $submittedLabel = collect($question->choices)->firstWhere('value', $submittedValue)['label'] ?? $submittedValue;
                    $expectedLabel = collect($question->choices)->firstWhere('value', $expectedValue)['label'] ?? $expectedValue;
                    $isCorrect = $question->answer?->is_correct === true;
                @endphp
                <article class="nq-review-item {{ $isCorrect ? '' : 'is-miss' }}">
                    <span class="nq-review-num" aria-label="प्रश्न {{ $index + 1 }}">{{ $index + 1 }}</span>
                    <div style="min-width: 0;">
                        <h3 class="h6 mb-1">{{ $question->prompt_marathi }}</h3>
                        <div class="small">
                            <span style="color: var(--nq-ink-soft);">तुमचे उत्तर:</span>
                            <strong style="color: {{ $isCorrect ? 'var(--nq-leaf)' : 'var(--nq-orange-deep)' }};">{{ $submittedLabel ?? 'अनुत्तरित' }}</strong>
                            @unless ($isCorrect)
                                <span class="mx-1">·</span>
                                <span style="color: var(--nq-ink-soft);">योग्य उत्तर:</span>
                                <strong style="color: var(--nq-leaf);">{{ $expectedLabel }}</strong>
                            @endunless
                        </div>
                    </div>
                    <span class="nq-review-badge {{ $isCorrect ? 'nq-acc-hi' : 'nq-acc-lo' }}">
                        <i class="bi {{ $isCorrect ? 'bi-check-lg' : 'bi-arrow-repeat' }}" aria-hidden="true"></i>
                        {{ $isCorrect ? 'अचूक' : 'सराव करा' }}
                    </span>
                </article>
            @endforeach
        </section>
    </div>
</x-layouts.app>
