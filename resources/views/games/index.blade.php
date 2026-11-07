<x-layouts.app title="इयत्ता चौथी विशेष: डिजिटल अध्ययन खेळ व प्रगती मापन">
    <link rel="stylesheet" href="{{ asset('css/nipun-games.css') }}">

    <div class="nq-page">
        <!-- Island hero -->
        <section class="nq-hero">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div style="max-width: 46rem;">
                    <span class="nq-eyebrow">
                        <i class="bi bi-award-fill" aria-hidden="true"></i>
                        <span>शरद पवार इन्स्पायर फेलोशिप २०२६-२७ · जि. प. शाळा नं. २ कुची</span>
                    </span>
                    <h1 class="nq-hero-title">इयत्ता चौथी: <span>डिजिटल अध्ययन खेळ</span> व प्रगती मापन</h1>
                    <p class="nq-hero-lead">
                        निपुण महाराष्ट्र (SCERT) व NEP 2020 अध्ययन निष्पत्ती आधारित शैक्षणिक खेळ.
                        विद्यार्थ्यांचे स्वयंअध्ययन, आनंददायी खेळ आणि वस्तुनिष्ठ प्रगती मापन.
                    </p>
                </div>

                <div class="d-flex flex-column align-items-start align-items-md-end gap-3">
                    <span class="nq-pill-grade">
                        <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>इयत्ता: ४ थी (Class 4)
                    </span>
                    <a class="nq-btn nq-btn-light" href="{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? route('dashboard').'#learning-map' : route('fellowship.reports.index') }}">
                        <i class="bi bi-map-fill" aria-hidden="true"></i>{{ auth()->user()->hasRole(\App\Enums\RoleCode::Student) ? 'माझा अध्ययन नकाशा' : 'फेलोशिप अहवाल' }}
                    </a>
                </div>
            </div>

            <div class="nq-stats">
                <div class="nq-stat">
                    <small>उपलब्ध खेळ</small>
                    <strong>{{ $stats['total_games'] ?? $games->count() }} खेळ</strong>
                </div>
                <div class="nq-stat">
                    <small>सराव सत्रे पूर्ण</small>
                    <strong>{{ $stats['completed_sessions'] ?? 0 }} सत्रे</strong>
                </div>
                <div class="nq-stat">
                    <small>वर्ग सरासरी अचूकता</small>
                    <strong>{{ $stats['class_accuracy'] !== null ? number_format((float) $stats['class_accuracy'], 1).'%' : '—' }}</strong>
                </div>
                <div class="nq-stat">
                    <small>पुढचे पाऊल</small>
                    <strong>खेळातून शिकूया</strong>
                </div>
            </div>
        </section>

        <!-- Flagship AppleMath -->
        <section class="nq-feature">
            <div class="d-flex align-items-center gap-3">
                <div class="nq-feature-mark" aria-hidden="true"><i class="bi bi-basket2-fill"></i></div>
                <div>
                    <div class="d-flex flex-wrap gap-2 mb-1">
                        <span class="nq-tag nq-tag-hot">विशेष गणित खेळ</span>
                        <span class="nq-tag">श्री. अमोल किसन हंकारे निर्मित</span>
                    </div>
                    <h2 class="h4 mb-1" style="color: var(--nq-orange-deep);">AppleMath (सफरचंद गणित)</h2>
                    <p class="mb-0 small" style="color: var(--nq-ink-soft); max-width: 38rem;">
                        झाडावरील सफरचंद मोजून टोपलीत टाकणे, हातच्याची बेरीज, वजाबाकी आणि गुणाकार संकल्पनांचे परस्परसंवादी डिजिटल मॉडेल.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <a class="nq-btn nq-btn-ghost" href="https://games.techedu.in/applemath/" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>थेट वेब व्हर्जन (TechEdu)
                </a>
                @php
                    $appleGame = $games->firstWhere('code', 'ADDITION_ADVENTURE') ?? $games->first();
                @endphp
                @if ($appleGame)
                    <form method="POST" action="{{ route('games.start', $appleGame) }}">
                        @csrf
                        <button class="nq-btn nq-btn-orange" type="submit">
                            <i class="bi bi-play-fill" aria-hidden="true"></i>पोर्टलवर खेळा
                        </button>
                    </form>
                @endif
            </div>
        </section>

        <!-- Filters -->
        <div class="nq-filters" role="group" aria-label="खेळ वर्गवारी">
            <button class="btn nq-filter btn-primary active" type="button" data-filter="all">
                <i class="bi bi-stars me-1" aria-hidden="true"></i>सर्व खेळ ({{ $games->count() }})
            </button>
            <button class="btn nq-filter btn-outline-secondary" type="button" data-filter="applemath">
                <i class="bi bi-basket2 me-1" aria-hidden="true"></i>AppleMath व मूलभूत क्रिया
            </button>
            <button class="btn nq-filter btn-outline-secondary" type="button" data-filter="math">
                <i class="bi bi-123 me-1" aria-hidden="true"></i>संख्याज्ञान व भूमिती
            </button>
            <button class="btn nq-filter btn-outline-secondary" type="button" data-filter="marathi">
                <i class="bi bi-book me-1" aria-hidden="true"></i>मराठी भाषा व व्याकरण
            </button>
        </div>

        <!-- Games grid -->
        <div class="row g-4" id="gamesGrid">
            @forelse ($games as $game)
                @php
                    $firstLevel = $game->levels->first();
                    $config = $game->configuration ?? [];
                    $category = data_get($config, 'category', 'math');
                    $icon = data_get($config, 'icon', '★');
                    $flnBadge = data_get($config, 'fln_badge', 'इयत्ता ४ थी');
                    $competency = data_get($config, 'competency', $game->skills->first()?->name_marathi);
                @endphp
                <div class="col-sm-6 col-xl-4 game-item-col" data-category="{{ $category }}">
                    <article class="nq-card game-catalog-card" data-cat="{{ $category }}">
                        <div class="nq-card-top d-flex justify-content-between align-items-start gap-2">
                            <div class="nq-card-icon game-catalog-icon" aria-hidden="true">{{ $icon }}</div>
                            <span class="nq-tag">{{ $flnBadge }}</span>
                        </div>

                        <div class="nq-card-body">
                            <h2 class="nq-card-title">{{ $game->title_marathi }}</h2>
                            <div class="nq-card-sub">{{ $game->title }}</div>

                            @if ($competency)
                                <div class="nq-competency">
                                    <i class="bi bi-check2-circle" aria-hidden="true"></i><span>{{ $competency }}</span>
                                </div>
                            @endif

                            <p class="nq-card-desc">{{ $game->description_marathi ?: $game->description }}</p>

                            <div class="nq-meta">
                                <span><i class="bi bi-layers-fill me-1" aria-hidden="true"></i>{{ $game->levels->count() }} पातळ्या</span>
                                <span><i class="bi bi-heart-fill me-1" aria-hidden="true"></i>{{ data_get($firstLevel?->configuration, 'lives', 3) }} संधी</span>
                                @if ($firstLevel?->time_limit_seconds)
                                    <span><i class="bi bi-stopwatch-fill me-1" aria-hidden="true"></i>{{ $firstLevel->time_limit_seconds }} सेकंद</span>
                                @endif
                            </div>
                        </div>

                        <div class="nq-card-foot">
                            <form method="POST" action="{{ route('games.start', $game) }}">
                                @csrf
                                <button class="nq-btn nq-btn-orange w-100" type="submit">
                                    <i class="bi bi-play-fill" aria-hidden="true"></i>खेळ सुरू करा
                                </button>
                            </form>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="nq-empty">
                        <i class="bi bi-compass d-block mb-2" aria-hidden="true"></i>
                        <p class="mb-0 fw-semibold">इयत्ता ४ थी साठी सध्या कोणताही प्रकाशित अध्ययन खेळ उपलब्ध नाही.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Recent attempts -->
        @if ($recentSessions->isNotEmpty())
            <section class="nq-panel">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <div>
                        <h2 class="h5 mb-1">इयत्ता ४ थी: अलीकडील खेळ सत्रे</h2>
                        <p class="small mb-0" style="color: var(--nq-ink-soft);">विद्यार्थ्यांनी सोडवलेले खेळ व नोंदवलेले प्रगती निकाल</p>
                    </div>
                    <span class="nq-tag nq-tag-hot"><i class="bi bi-graph-up" aria-hidden="true"></i>थेट निकाल</span>
                </div>
                <div class="table-responsive">
                    <table class="nq-table">
                        <thead>
                            <tr>
                                <th>खेळ</th>
                                <th>विद्यार्थी</th>
                                <th>प्राप्त गुण</th>
                                <th>अचूकता</th>
                                <th>मिळालेले XP</th>
                                <th>वेळ</th>
                                <th class="text-end">कृती</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                @php
                                    $acc = (float) ($session->result?->accuracy ?? 0);
                                    $accClass = $acc >= 80 ? 'nq-acc-hi' : ($acc >= 50 ? 'nq-acc-mid' : 'nq-acc-lo');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fs-5" aria-hidden="true">{{ data_get($session->game->configuration, 'icon', '★') }}</span>
                                            <div>
                                                <div class="fw-bold">{{ $session->game->title_marathi }}</div>
                                                <div class="small" style="color: var(--nq-ink-soft);">{{ $session->game->title }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="fw-semibold">{{ $session->student->user->name ?? 'इयत्ता ४ थी विद्यार्थी' }}</span></td>
                                    <td>
                                        <strong style="color: var(--nq-orange-deep);">{{ $session->result?->score ?? 0 }}</strong>
                                        <span class="small" style="color: var(--nq-ink-soft);">/ {{ $session->result?->max_score ?? 0 }}</span>
                                    </td>
                                    <td><span class="nq-acc {{ $accClass }}">{{ number_format($acc, 0) }}%</span></td>
                                    <td><span class="fw-bold" style="color: var(--nq-leaf);">+{{ $session->result?->xp_awarded ?? 0 }} XP</span></td>
                                    <td><span class="small" style="color: var(--nq-ink-soft);">{{ $session->completed_at?->diffForHumans() ?? 'काही वेळापूर्वी' }}</span></td>
                                    <td class="text-end">
                                        <a class="nq-btn nq-btn-ghost py-1 px-3 small" href="{{ route('games.sessions.result', $session) }}">निकाल कार्ड पहा</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>

    <!-- JavaScript Filter for Tabs -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('[data-filter]');
            const items = document.querySelectorAll('.game-item-col');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(b => {
                        b.classList.remove('btn-primary', 'active');
                        b.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-primary', 'active');

                    const filter = this.dataset.filter;
                    items.forEach(item => {
                        if (filter === 'all' || item.dataset.category === filter) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-layouts.app>
