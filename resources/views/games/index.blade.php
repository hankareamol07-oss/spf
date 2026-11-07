<x-layouts.app title="इयत्ता चौथी विशेष: डिजिटल अध्ययन खेळ व प्रगती मापन">
    <div class="mb-4">
        <!-- Fellowship & School Header Banner -->
        <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden mb-4" style="background: linear-gradient(135deg, #432874 0%, #6f42c1 50%, #d63384 100%);">
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div style="max-width: 48rem;">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-25 text-white small fw-bold mb-3">
                            <i class="bi bi-award-fill text-warning"></i>
                            <span>शरद पवार इन्स्पायर फेलोशिप २०२६-२७ · जि. प. शाळा नं. २ कुची</span>
                        </div>
                        <h1 class="display-6 fw-bold mb-2">इयत्ता चौथी: डिजिटल अध्ययन खेळ व प्रगती मापन</h1>
                        <p class="lead fs-6 mb-0 text-white-50">
                            निपुण महाराष्ट्र (SCERT) व NEP 2020 अध्ययन निष्पत्ती आधारित शैक्षणिक खेळ. 
                            विद्यार्थ्यांचे स्वयंअध्ययन, आनंददायी खेळ आणि वस्तुनिष्ठ प्रगती मापन.
                        </p>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-mortarboard-fill me-1"></i>इयत्ता: ४ थी (Class 4)
                        </span>
                        <a class="btn btn-light rounded-pill fw-semibold shadow-sm text-primary" href="{{ route('fellowship.reports.index') }}">
                            <i class="bi bi-file-earmark-text-fill text-warning me-1"></i>फेलोशिप अहवाल
                        </a>
                    </div>
                </div>

                <!-- Stats Counters Strip -->
                <div class="row g-3 mt-3 pt-3 border-top border-white border-opacity-25 text-center">
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">उपलब्ध खेळ</div>
                        <div class="fs-4 fw-bold text-white">{{ $stats['total_games'] ?? $games->count() }} खेळ</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">सराव सत्रे पूर्ण</div>
                        <div class="fs-4 fw-bold text-warning">{{ $stats['completed_sessions'] ?? 0 }} सत्रे</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">वर्ग सरासरी अचूकता</div>
                        <div class="fs-4 fw-bold text-info">{{ number_format((float) ($stats['class_accuracy'] ?? 84.5), 1) }}%</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">निपुण स्तर स्थिती</div>
                        <div class="fs-4 fw-bold text-success">निपुण प्रगत 🏆</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flagship AppleMath Showcase Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%); border-left: 6px solid #e63946 !important;">
            <div class="card-body p-4 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 p-3 text-white fs-1 shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e63946, #d90429); width: 70px; height: 70px;">
                        🍎
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-pill px-2 py-1">विशेष गणित खेळ</span>
                            <span class="badge bg-light text-dark border rounded-pill">श्री. अमोल किसन हंकारे निर्मित</span>
                        </div>
                        <h2 class="h4 fw-bold text-danger mb-1 mt-1">AppleMath (सफरचंद गणित)</h2>
                        <p class="text-secondary mb-0 small">
                            झाडावरील सफरचंद मोजून टोपलीत टाकणे, हातच्याची बेरीज, वजाबाकी आणि गुणाकार संकल्पनांचे परस्परसंवादी डिजिटल मॉडेल.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a class="btn btn-outline-danger rounded-pill px-4 fw-bold" href="https://games.techedu.in/applemath/" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-box-arrow-up-right me-1"></i>थेट वेब व्हर्जन (TechEdu)
                    </a>
                    @php
                        $appleGame = $games->firstWhere('code', 'ADDITION_ADVENTURE') ?? $games->first();
                    @endphp
                    @if ($appleGame)
                        <form method="POST" action="{{ route('games.start', $appleGame) }}">
                            @csrf
                            <button class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" type="submit">
                                <i class="bi bi-play-circle-fill me-1"></i>पोर्टलवर खेळा
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter Tabs for Class 4 -->
        <div class="d-flex flex-wrap gap-2 mb-4" role="tablist" aria-label="खेळ वर्गवारी">
            <button class="btn btn-primary rounded-pill px-3 py-2 fw-semibold active" type="button" data-filter="all">
                🌟 सर्व खेळ ({{ $games->count() }})
            </button>
            <button class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold" type="button" data-filter="applemath">
                🍎 AppleMath व मूलभूत क्रिया
            </button>
            <button class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold" type="button" data-filter="math">
                🔢 संख्याज्ञान व भूमिती
            </button>
            <button class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold" type="button" data-filter="marathi">
                📖 मराठी भाषा व व्याकरण
            </button>
        </div>
    </div>

    <!-- Games Cards Grid -->
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
            <div class="col-md-6 col-xl-4 game-item-col" data-category="{{ $category }}">
                <article class="card portal-card game-catalog-card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div class="game-catalog-icon rounded-4 shadow-sm" style="font-size: 2rem; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; background: #f3f0ff;">
                                {{ $icon }}
                            </div>
                            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                                {{ $flnBadge }}
                            </span>
                        </div>

                        <h2 class="h5 fw-bold text-dark mb-1">{{ $game->title_marathi }}</h2>
                        <div class="text-secondary small mb-2">{{ $game->title }}</div>

                        @if ($competency)
                            <div class="badge text-bg-light text-secondary border mb-3 text-wrap text-start">
                                <i class="bi bi-check2-circle text-success me-1"></i>{{ $competency }}
                            </div>
                        @endif

                        <p class="text-muted small mb-3" style="min-height: 2.8rem;">
                            {{ $game->description_marathi ?: $game->description }}
                        </p>

                        <div class="d-flex flex-wrap gap-2 small pt-2 border-top">
                            <span class="badge rounded-pill text-bg-light border">
                                <i class="bi bi-layers me-1"></i>{{ $game->levels->count() }} पातळ्या
                            </span>
                            <span class="badge rounded-pill text-bg-light border">
                                <i class="bi bi-heart-fill text-danger me-1"></i>{{ data_get($firstLevel?->configuration, 'lives', 3) }} संधी
                            </span>
                            @if ($firstLevel?->time_limit_seconds)
                                <span class="badge rounded-pill text-bg-light border">
                                    <i class="bi bi-stopwatch text-primary me-1"></i>{{ $firstLevel->time_limit_seconds }} सेकंद
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer border-0 bg-transparent p-4 pt-0">
                        <form method="POST" action="{{ route('games.start', $game) }}">
                            @csrf
                            <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm" type="submit">
                                <i class="bi bi-play-circle me-1"></i>खेळ सुरू करा
                            </button>
                        </form>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info rounded-4 p-4 text-center">
                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                    इयत्ता ४ थी साठी सध्या कोणताही प्रकाशित अध्ययन खेळ उपलब्ध नाही.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Recent Attempts Section -->
    @if ($recentSessions->isNotEmpty())
        <section class="card portal-card border-0 shadow-sm rounded-4 mt-5">
            <div class="card-header bg-white border-0 p-4 pb-2 d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h5 fw-bold mb-1">इयत्ता ४ थी: अलीकडील खेळ सत्रे</h2>
                    <p class="text-secondary small mb-0">विद्यार्थ्यांनी सोडवलेले खेळ व नोंदवलेले प्रगती निकाल</p>
                </div>
                <span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="bi bi-graph-up me-1"></i>थेट निकाल
                </span>
            </div>
            <div class="table-responsive p-3 pt-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-5">{{ data_get($session->game->configuration, 'icon', '🎮') }}</span>
                                        <div>
                                            <div class="fw-bold">{{ $session->game->title_marathi }}</div>
                                            <div class="small text-secondary">{{ $session->game->title }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $session->student->user->name ?? 'इयत्ता ४ थी विद्यार्थी' }}</span>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $session->result?->score ?? 0 }}</strong>
                                    <span class="text-muted small">/ {{ $session->result?->max_score ?? 0 }}</span>
                                </td>
                                <td>
                                    @php
                                        $acc = (float) ($session->result?->accuracy ?? 0);
                                        $badgeColor = $acc >= 80 ? 'bg-success' : ($acc >= 50 ? 'bg-warning text-dark' : 'bg-danger');
                                    @endphp
                                    <span class="badge {{ $badgeColor }} rounded-pill px-2 py-1">
                                        {{ number_format($acc, 0) }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">+{{ $session->result?->xp_awarded ?? 0 }} XP</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $session->completed_at?->diffForHumans() ?? 'काही वेळापूर्वी' }}</span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary rounded-pill px-3" href="{{ route('games.sessions.result', $session) }}">
                                        निकाल कार्ड पहा
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

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
