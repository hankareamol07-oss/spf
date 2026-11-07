<x-layouts.app :title="$gameSession->game->title_marathi . ' - प्रगती निकाल (इयत्ता ४ थी)'">
    @php
        $result = $gameSession->result;
        $acc = (float) ($result->accuracy ?? 0);
        $isMastered = $acc >= 80;
        $isDeveloping = $acc >= 50 && $acc < 80;
        $levelTitle = $isMastered ? 'निपुण प्रगत स्तर (Mastered)' : ($isDeveloping ? 'प्रगतीपथावर (Developing)' : 'सराव आवश्यक (Needs Practice)');
        $levelBadge = $isMastered ? 'bg-success' : ($isDeveloping ? 'bg-warning text-dark' : 'bg-danger');
        $stars = $isMastered ? '⭐⭐⭐' : ($isDeveloping ? '⭐⭐☆' : '⭐☆☆');
        $competency = data_get($gameSession->game->configuration, 'competency', 'इयत्ता ४ थी अध्ययन निष्पत्ती');
    @endphp

    <!-- Hero Result Card -->
    <section class="card portal-card border-0 shadow-sm rounded-4 mb-4 p-4 p-lg-5 text-center position-relative overflow-hidden" style="background: linear-gradient(180deg, #f8f6fe 0%, #ffffff 100%);">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary bg-opacity-10 text-primary small fw-bold mb-3">
            <i class="bi bi-mortarboard-fill"></i>
            <span>इयत्ता ४ थी शैक्षणिक प्रगती मापन निकाल</span>
        </div>

        <div class="fs-1 mb-2" aria-hidden="true">{{ $stars }}</div>
        
        <h1 class="h2 fw-bold text-dark mb-1">{{ $gameSession->game->title_marathi }}</h1>
        <div class="text-secondary mb-2">{{ $gameSession->game->title }}</div>

        <div class="d-inline-block">
            <span class="badge {{ $levelBadge }} fs-6 px-4 py-2 rounded-pill shadow-sm mb-3">
                {{ $levelTitle }}
            </span>
        </div>

        <div class="small text-muted mb-4">
            <i class="bi bi-check-circle-fill text-success me-1"></i>मूल्यांकित कौशल्य: <strong>{{ $competency }}</strong>
        </div>

        <!-- Metric Stat Tiles -->
        <div class="row g-3 justify-content-center mt-2 max-w-4xl mx-auto">
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-white shadow-sm rounded-3 p-3 text-center h-100">
                    <span class="small text-secondary fw-semibold">प्राप्त गुण</span>
                    <strong class="fs-3 fw-bold text-primary">{{ $result->score }}</strong>
                    <span class="small text-muted">कमाल: {{ $result->max_score }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-white shadow-sm rounded-3 p-3 text-center h-100">
                    <span class="small text-secondary fw-semibold">अचूकता (Accuracy)</span>
                    <strong class="fs-3 fw-bold {{ $isMastered ? 'text-success' : ($isDeveloping ? 'text-warning' : 'text-danger') }}">
                        {{ number_format($acc, 0) }}%
                    </strong>
                    <span class="small text-muted">लक्ष्य: ८०%+</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-white shadow-sm rounded-3 p-3 text-center h-100">
                    <span class="small text-secondary fw-semibold">अचूक उत्तरे</span>
                    <strong class="fs-3 fw-bold text-success">{{ $result->correct_count }} / {{ $gameSession->questions->count() }}</strong>
                    <span class="small text-muted">प्रश्न अचूक</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 bg-white shadow-sm rounded-3 p-3 text-center h-100">
                    <span class="small text-secondary fw-semibold">कमावलेले XP</span>
                    <strong class="fs-3 fw-bold text-warning">+{{ $result->xp_awarded }}</strong>
                    <span class="small text-muted">गुणवत्ता बोनस</span>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4 pt-2">
            <form method="POST" action="{{ route('games.start', $gameSession->game) }}">
                @csrf
                <button class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm w-100" type="submit">
                    <i class="bi bi-arrow-repeat me-1"></i>पुन्हा खेळा
                </button>
            </form>
            <a class="btn btn-outline-primary btn-lg rounded-pill px-4 fw-semibold" href="{{ route('games.index') }}">
                <i class="bi bi-grid-fill me-1"></i>इयत्ता ४ थी चे इतर खेळ
            </a>
            <a class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-semibold" href="{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? route('dashboard').'#learning-map' : route('fellowship.reports.index') }}">
                <i class="bi bi-map me-1"></i>{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? 'माझा अध्ययन नकाशा' : 'फेलोशिप अहवाल' }}
            </a>
        </div>
    </section>

    <!-- Question-by-Question Review -->
    <section class="card portal-card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <h2 class="h5 fw-bold mb-1">प्रश्नोत्तर विश्लेषण व आढावा</h2>
            <p class="text-secondary small mb-0">प्रत्येक प्रश्नाचे नोंदवलेले उत्तर आणि अचूक पडताळणी</p>
        </div>
        <div class="list-group list-group-flush p-3 pt-0">
            @foreach ($gameSession->questions as $index => $question)
                @php
                    $submittedValue = data_get($question->answer?->answer, 'value');
                    $expectedValue = data_get($question->expected_answer, 'value');
                    $submittedLabel = collect($question->choices)->firstWhere('value', $submittedValue)['label'] ?? $submittedValue;
                    $expectedLabel = collect($question->choices)->firstWhere('value', $expectedValue)['label'] ?? $expectedValue;
                    $isCorrect = $question->answer?->is_correct === true;
                @endphp
                <article class="list-group-item p-3 p-md-4 rounded-3 mb-2 border {{ $isCorrect ? 'bg-light bg-opacity-50' : 'bg-danger bg-opacity-10 border-danger border-opacity-25' }}">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="small text-muted fw-bold mb-1">प्रश्न {{ $index + 1 }}</div>
                            <h3 class="h6 mb-2 fw-bold text-dark">{{ $question->prompt_marathi }}</h3>
                            <div class="small">
                                <span class="text-secondary">तुमचे उत्तर:</span>
                                <strong class="{{ $isCorrect ? 'text-success' : 'text-danger' }}">
                                    {{ $submittedLabel ?? 'अनुत्तरित' }}
                                </strong>
                                @unless ($isCorrect)
                                    <span class="mx-1">·</span>
                                    <span class="text-secondary">योग्य उत्तर:</span>
                                    <strong class="text-success">{{ $expectedLabel }}</strong>
                                @endunless
                            </div>
                        </div>
                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                            {{ $isCorrect ? 'अचूक ✓' : 'सराव करा ✗' }}
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>
