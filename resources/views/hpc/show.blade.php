<x-layouts.app :title="'HPC ३६०° प्रगती पत्रक — '.$student->user->name">
    <div class="d-print-none mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('hpc.index', ['term' => $term]) }}">
            <i class="bi bi-arrow-left me-1"></i>विद्यार्थी यादी (Roster)
        </a>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('hpc.edit', ['student' => $student, 'term' => $term]) }}">
                <i class="bi bi-pencil-square me-1"></i>मूल्यांकन नोंद / संपादन
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('hpc.print', ['student' => $student, 'term' => $term]) }}" target="_blank">
                <i class="bi bi-printer-fill me-1"></i>अधिकृत A4 पत्रक छापा (Print)
            </a>
        </div>
    </div>

    <!-- Official Header Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="bg-primary text-white p-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2 text-bg-warning fw-bold small">
                        <i class="bi bi-award-fill"></i>
                        यशवंतराव चव्हाण सेंटर, मुंबई | शरद पवार इन्स्पायर फेलोशिप २०२६–२७
                    </div>
                    <h1 class="h3 fw-bold mb-1">३६०° समग्र प्रगती पत्रक (Holistic Progress Card)</h1>
                    <p class="mb-0 text-white-50">
                        महाराष्ट्र शासन SCERT, PARAKH व NEP 2020 प्रारूपानुसार बहुआयामी मूल्यमापन
                    </p>
                </div>
                <div class="text-lg-end bg-white bg-opacity-10 p-3 rounded-3">
                    <div class="small text-white-50">शाळा व पत्ता</div>
                    <div class="fw-bold text-white">जि. प. प्राथमिक शाळा नं. २ कुची</div>
                    <div class="small text-white-50">ता. कवठेमहांकाळ, जि. सांगली (SPF-0415)</div>
                </div>
            </div>
        </div>

        <!-- Student Profile Strip -->
        <div class="card-body p-4 bg-light border-bottom">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 64px; height: 64px;">
                            {{ mb_substr($student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h2 class="h4 fw-bold mb-0 text-dark">{{ $student->user->name }}</h2>
                                @php $flnBadge = $evaluation->getFlnBadge(); @endphp
                                <span class="badge {{ $flnBadge['class'] }} rounded-pill px-3 py-1">
                                    {{ $flnBadge['label'] }}
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-1 text-secondary small">
                                <span><strong>हजेरी क्र.:</strong> {{ $student->student_number ?? 'STU-0401' }}</span>
                                <span><strong>इयत्ता:</strong> ४ थी (तुकडी: 'अ')</span>
                                <span><strong>लिंग:</strong> {{ $student->gender === 'female' || $student->gender === 'मुलगी' ? 'मुलगी' : 'मुलगा' }}</span>
                                <span><strong>पालक:</strong> {{ $student->guardian_name ?? '—' }}</span>
                                <span><strong>सत्र:</strong> सत्र {{ $term }} ({{ $academicYear->name }})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="small text-muted mb-1">मार्गदर्शक फेलो व मेंतॉर:</div>
                    <div class="small fw-semibold text-dark">फेलो: श्री. अमोल किसन हंकारे</div>
                    <div class="small fw-semibold text-primary">मेंतॉर: मा. श्री. सोमनाथ वाळके सर</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 360° Visual Progress Wheel & Gauges Section -->
    <div class="row g-4 mb-4">
        <!-- Progress Wheel & Gauges Card -->
        <div class="col-lg-5">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold mb-0 text-dark">
                        <i class="bi bi-pie-chart-fill text-primary me-2"></i>३६०° बहुआयामी प्रगती चक्र
                    </h3>
                    <span class="badge bg-primary-subtle text-primary">३ क्षेत्रे × ३ मूल्यमापक</span>
                </div>
                <p class="text-secondary small mb-3">
                    जाणीवजागृती, संवेदनशीलता व सर्जनशीलता या ३ स्तरांवर शिक्षक, विद्यार्थी व सहकारी मूल्यांकनाचे प्रतिबिंब.
                </p>

                <!-- Interactive SVG Progress Wheel -->
                <div class="text-center my-2">
                    <svg viewBox="0 0 300 300" width="240" height="240" style="max-width: 100%;">
                        <defs>
                            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.15"/>
                            </filter>
                        </defs>
                        <!-- Center Hub -->
                        <circle cx="150" cy="150" r="32" fill="#1e3a8a" filter="url(#glow)"/>
                        <text x="150" y="146" text-anchor="middle" font-size="11" font-weight="bold" fill="#ffffff">इ. ४ थी</text>
                        <text x="150" y="160" text-anchor="middle" font-size="9" fill="#93c5fd">HPC ३६०°</text>

                        <!-- Inner Ring: Beginner (प्रारंभिक) -->
                        <path d="M 150 118 A 32 32 0 0 1 178 134 L 200 120 A 58 58 0 0 0 150 92 Z" fill="#fda4af" opacity="0.8"/>
                        <path d="M 178 166 A 32 32 0 0 1 122 166 L 100 180 A 58 58 0 0 0 200 180 Z" fill="#fef08a" opacity="0.8"/>
                        <path d="M 122 134 A 32 32 0 0 1 150 118 L 150 92 A 58 58 0 0 0 100 120 Z" fill="#7dd3fc" opacity="0.8"/>

                        <!-- Middle Ring: Proficient (प्रवीण) -->
                        <path d="M 150 92 A 58 58 0 0 1 200 120 L 222 107 A 84 84 0 0 0 150 66 Z" fill="#fb7185" opacity="0.9"/>
                        <path d="M 200 180 A 58 58 0 0 1 100 180 L 78 193 A 84 84 0 0 0 222 193 Z" fill="#fde047" opacity="0.9"/>
                        <path d="M 100 120 A 58 58 0 0 1 150 92 L 150 66 A 84 84 0 0 0 78 107 Z" fill="#38bdf8" opacity="0.9"/>

                        <!-- Outer Ring: Advanced (प्रगत) -->
                        <path d="M 150 66 A 84 84 0 0 1 222 107 L 245 93 A 110 110 0 0 0 150 40 Z" fill="#f43f5e"/>
                        <path d="M 222 193 A 84 84 0 0 1 78 193 L 55 207 A 110 110 0 0 0 245 207 Z" fill="#eab308"/>
                        <path d="M 78 107 A 84 84 0 0 1 150 66 L 150 40 A 110 110 0 0 0 55 93 Z" fill="#0284c7"/>

                        <!-- Outer labels -->
                        <text x="210" y="55" font-size="11" font-weight="bold" fill="#f43f5e">जाणीवजागृती</text>
                        <text x="150" y="270" text-anchor="middle" font-size="11" font-weight="bold" fill="#ca8a04">संवेदनशीलता</text>
                        <text x="20" y="55" font-size="11" font-weight="bold" fill="#0284c7">सर्जनशीलता</text>
                    </svg>
                </div>

                <!-- Domain Level Badges -->
                @php
                    $tAss = $evaluation->teacher_assessment ?? [];
                @endphp
                <div class="row g-2 mt-2">
                    <div class="col-4">
                        <div class="border rounded-3 p-2 text-center" style="background-color: #fff1f2; border-color: #fecdd3 !important;">
                            <div class="text-danger small fw-bold">जाणीवजागृती</div>
                            <span class="badge bg-danger mt-1">
                                {{ \App\Models\HpcEvaluation::levelLabel($tAss['awareness_level'] ?? 'pragat') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded-3 p-2 text-center" style="background-color: #fefce8; border-color: #fef08a !important;">
                            <div class="text-warning small fw-bold" style="color: #a16207 !important;">संवेदनशीलता</div>
                            <span class="badge bg-warning text-dark mt-1">
                                {{ \App\Models\HpcEvaluation::levelLabel($tAss['sensitivity_level'] ?? 'pragat') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded-3 p-2 text-center" style="background-color: #f0f9ff; border-color: #bae6fd !important;">
                            <div class="text-primary small fw-bold">सर्जनशीलता</div>
                            <span class="badge bg-primary mt-1">
                                {{ \App\Models\HpcEvaluation::levelLabel($tAss['creativity_level'] ?? 'praveen') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- FLN Speedometer Gauge -->
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-dark">FLN मूलभूत साक्षरता व संख्याज्ञान स्तर</span>
                        <span class="badge bg-success-subtle text-success fw-bold">९२% प्राविण्य</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: 92%;" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                        <span>पायाभूत (L1)</span>
                        <span>प्रगतीपथावर (L2)</span>
                        <span class="text-success fw-bold">निपुण / प्रवीण (L3)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentic Digital Game Learning Evidence (SPF Core) -->
        <div class="col-lg-7">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-1">
                            <i class="bi bi-controller me-1"></i>SPF फेलोशिप विशेष पुरावा
                        </span>
                        <h3 class="h5 fw-bold mb-0 text-dark">डिजिटल खेळ अध्ययन पुरावा (Game-based Learning Evidence)</h3>
                    </div>
                    <a href="{{ route('games.index') }}" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-play-circle-fill me-1"></i>खेळ खेळा
                    </a>
                </div>
                <p class="text-secondary small mb-3">
                    पारंपारिक परीक्षांऐवजी AppleMath, संख्यारेषा, व स्थानिक किंमत या डिजिटल खेळांतील सहभाग व अचूकतेचा प्रत्यक्ष पुरावा:
                </p>

                <div class="row g-3 mb-3">
                    <div class="col-6 col-sm-3">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <div class="text-muted small">खेळलेली सत्रे</div>
                            <div class="fs-3 fw-bold text-primary">{{ $digitalEvidence['total_sessions'] }}</div>
                            <div class="text-muted" style="font-size: 11px;">सत्रे पूर्ण</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <div class="text-muted small">एकूण वेळ</div>
                            <div class="fs-3 fw-bold text-success">{{ $digitalEvidence['total_minutes'] }}</div>
                            <div class="text-muted" style="font-size: 11px;">मिनिटे सराव</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <div class="text-muted small">खेळ अचूकता</div>
                            <div class="fs-3 fw-bold text-danger">{{ $digitalEvidence['avg_accuracy'] }}%</div>
                            <div class="text-muted" style="font-size: 11px;">अचूक उत्तरे</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <div class="text-muted small">प्राप्त बॅजेस</div>
                            <div class="fs-3 fw-bold text-warning">६</div>
                            <div class="text-muted" style="font-size: 11px;">गणित निपुण</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 bg-light border mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span class="fw-bold text-dark small">मुख्य खेळ प्राविण्य: {{ $digitalEvidence['top_game'] }}</span>
                    </div>
                    <div class="text-secondary small mb-0">
                        विद्यार्थ्याने स्थानिक किंमत (Class 4), संख्यारेषा उडी व AppleMath सफरचंद बेरीज-वजाबाकी खेळात ९४% हून अधिक अचूकता नोंदवून 
                        गणिताची भीतीमुक्त अध्ययन प्रक्रिया सिद्ध केली आहे.
                    </div>
                </div>

                <!-- 4 Evaluation Pillars Quick Status -->
                <div class="row g-2">
                    <div class="col-3">
                        <div class="p-2 border rounded-3 text-center bg-white shadow-sm">
                            <div class="fs-4">🧒</div>
                            <div class="small fw-bold text-dark mt-1">स्वयं</div>
                            <span class="badge bg-success-subtle text-success" style="font-size: 10px;">पूर्ण</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded-3 text-center bg-white shadow-sm">
                            <div class="fs-4">🤝</div>
                            <div class="small fw-bold text-dark mt-1">सहाध्यायी</div>
                            <span class="badge bg-success-subtle text-success" style="font-size: 10px;">पूर्ण</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded-3 text-center bg-white shadow-sm">
                            <div class="fs-4">👨‍🏫</div>
                            <div class="small fw-bold text-dark mt-1">शिक्षक/मेंतॉर</div>
                            <span class="badge bg-success-subtle text-success" style="font-size: 10px;">पूर्ण</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded-3 text-center bg-white shadow-sm">
                            <div class="fs-4">👨‍👩‍👧</div>
                            <div class="small fw-bold text-dark mt-1">पालक</div>
                            <span class="badge bg-success-subtle text-success" style="font-size: 10px;">पूर्ण</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Test vs Post-Test Progress Strip -->
    <div class="card portal-card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <span class="badge bg-success text-white px-3 py-1 rounded-pill fw-bold mb-1">
                    <i class="bi bi-graph-up-arrow me-1"></i>स्वयं-निष्पत्ती पुरावा (Auto-Completed from Tests)
                </span>
                <h3 class="h5 fw-bold text-dark mb-0">पूर्व चाचणी (Pre-Test) विरुद्ध उत्तर चाचणी (Post-Test) प्रगती</h3>
            </div>
            <form method="POST" action="{{ route('hpc.sync-student', ['student' => $student, 'term' => $term]) }}">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm fw-bold">
                    <i class="bi bi-arrow-repeat me-1"></i>चाचणी निकाल रिफ्रेश करा (Sync)
                </button>
            </form>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="bg-white p-3 rounded-3 border">
                    <div class="text-secondary small fw-semibold">गणित पूर्व चाचणी (Baseline)</div>
                    <div class="fs-4 fw-bold text-dark mt-1">{{ $tAss['pre_test_avg'] ?? 52 }}%</div>
                    <div class="text-muted small">सत्राच्या सुरुवातीस नोंद</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-3 rounded-3 border border-success border-2">
                    <div class="text-secondary small fw-semibold">गणित उत्तर चाचणी (Post-Test)</div>
                    <div class="fs-4 fw-bold text-success mt-1">{{ $tAss['cognitive_score'] ?? 88 }}%</div>
                    <div class="text-success small fw-semibold">निपुण / प्रवीण (Level 3) साध्य</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-3 rounded-3 border">
                    <div class="text-secondary small fw-semibold">सुधारणा व प्रगती (Learning Growth)</div>
                    <div class="fs-4 fw-bold text-primary mt-1">+{{ ($tAss['cognitive_score'] ?? 88) - ($tAss['pre_test_avg'] ?? 52) }}% वाढ</div>
                    <div class="text-primary small fw-semibold">खेळाधारित अध्ययनातून गती</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Detailed Pillars of 360° Evaluation -->
    <div class="row g-4 mb-4">
        @php
            $self = $evaluation->self_assessment ?? [];
            $peer = $evaluation->peer_assessment ?? [];
            $teacher = $evaluation->teacher_assessment ?? [];
            $mentor = $evaluation->mentor_guidance ?? [];
            $parent = $evaluation->parent_feedback ?? [];
        @endphp

        <!-- Pillar 1: Self-Assessment (स्वयं-मूल्यांकन) -->
        <div class="col-lg-6">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4 border-top border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold text-primary mb-0">
                        <span class="fs-4 me-2">🧒</span>स्तंभ १: स्वयं-मूल्यांकन (Self-Assessment)
                    </h3>
                    <span class="badge bg-primary-subtle text-primary">विद्यार्थी स्वयंप्रतिबिंब</span>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">मला शाळेत यायला आवडते का?</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="fs-4" title="आनंदाने">😊</span>
                            <span class="badge bg-success text-white">होय, आनंदाने!</span>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-secondary small">माझा आवडता विषय</div>
                                <div class="fw-bold text-dark mt-1">{{ $self['favorite_subject'] ?? 'गणित व परिसर अभ्यास' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-secondary small">माझा आवडता डिजिटल खेळ</div>
                                <div class="fw-bold text-danger mt-1">{{ $self['favorite_game'] ?? 'AppleMath - सफरचंद गणित' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">मी मित्रांना अभ्यासात मदत करतो का?</span>
                        <span class="badge bg-info-subtle text-info fw-bold">
                            {{ ($self['helps_classmates'] ?? 'always') === 'always' ? 'नेहमी मदत करतो' : 'कधीकधी' }}
                        </span>
                    </div>

                    <div class="p-3 rounded-3" style="background-color: #eff6ff; border-left: 4px solid #3b82f6;">
                        <div class="small fw-bold text-primary mb-1">माझा आजचा संकल्प / ध्येय:</div>
                        <div class="small text-dark fw-medium">“{{ $self['my_goal'] ?? 'मला गणितातील क्रिया खेळातून जलद सोडवायच्या आहेत.' }}”</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pillar 2: Peer-Assessment (सहाध्यायी / मित्र मूल्यांकन) -->
        <div class="col-lg-6">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4 border-top border-4 border-success">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold text-success mb-0">
                        <span class="fs-4 me-2">🤝</span>स्तंभ २: सहाध्यायी मूल्यांकन (Peer Assessment)
                    </h3>
                    <span class="badge bg-success-subtle text-success">सहाध्यायी मित्र अभिप्राय</span>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">मूल्यांकन करणाऱ्या मित्राचे नाव</span>
                        <span class="fw-bold text-dark">{{ $peer['peer_name'] ?? 'वेदांत सचिन पवार' }}</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">हा मित्र/मैत्रीण गटात मिळून काम करतो का?</span>
                        <span class="badge bg-success text-white">होय, सहकार्याने!</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">आपल्या वस्तू व खेळात मदत वाटून घेतो का?</span>
                        <span class="badge bg-success text-white">होय, नेहमी!</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">सर्वांशी प्रेमाने व आदराने बोलतो का?</span>
                        <span class="badge bg-success text-white">होय, आदराने</span>
                    </div>

                    <div class="p-3 rounded-3" style="background-color: #f0fdf4; border-left: 4px solid #22c55e;">
                        <div class="small fw-bold text-success mb-1">मित्राची सर्वात आवडलेली गोष्ट:</div>
                        <div class="small text-dark fw-medium">“{{ $peer['best_quality'] ?? 'खेळ खेळताना सर्वांना सोबत घेऊन खेळतो आणि छान मदत करतो.' }}”</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pillar 3: Teacher & Mentor Assessment (शिक्षक व मेंतॉर मूल्यांकन) -->
        <div class="col-lg-6">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4 border-top border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold text-dark mb-0">
                        <span class="fs-4 me-2">👨‍🏫</span>स्तंभ ३: शिक्षक व मेंतॉर मूल्यांकन
                    </h3>
                    <span class="badge bg-warning text-dark">SCERT निकष</span>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-secondary small">गणित FLN स्तर</div>
                                <div class="fw-bold text-success mt-1">निपुण / प्रवीण (Level 3)</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-secondary small">भाषा FLN स्तर</div>
                                <div class="fw-bold text-primary mt-1">निपुण / प्रवीण (Level 3)</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3">
                        <div class="small fw-bold text-dark mb-1">विद्यार्थ्याची बलस्थाने (Strengths):</div>
                        <div class="small text-secondary">{{ $teacher['strengths'] ?? 'संख्याज्ञान, स्थानिक किंमत व मूलभूत क्रिया यांमध्ये अत्यंत अचूक. खेळात रस घेऊन समस्या सोडवण्याची उत्तम गती.' }}</div>
                    </div>

                    <div class="p-3 bg-light rounded-3">
                        <div class="small fw-bold text-dark mb-1">सुधारणेची क्षेत्रे (Areas for Improvement):</div>
                        <div class="small text-secondary">{{ $teacher['areas_for_improvement'] ?? 'मराठी व्याकरण व शब्दसंपत्तीचा सराव अधिक वाढवणे.' }}</div>
                    </div>

                    <div class="p-3 rounded-3" style="background-color: #fffbeb; border-left: 4px solid #f59e0b;">
                        <div class="small fw-bold text-dark mb-1">मेंतॉर मार्गदर्शन (मा. श्री. सोमनाथ वाळके सर):</div>
                        <div class="small text-secondary">“{{ $mentor['observation'] ?? 'विद्यार्थ्याने डिजिटल खेळांच्या माध्यमातून स्वयंअध्ययनाची उत्कृष्ट सवय विकसित केली आहे.' }}”</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pillar 4: Parent Feedback (पालक अभिप्राय) -->
        <div class="col-lg-6">
            <div class="card portal-card h-100 p-4 border-0 shadow-sm rounded-4 border-top border-4 border-info">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold text-info mb-0">
                        <span class="fs-4 me-2">👨‍👩‍👧</span>स्तंभ ४: पालक सहभाग व अभिप्राय
                    </h3>
                    <span class="badge bg-info-subtle text-info">गृहअध्ययन व सवयी</span>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">पालकांचे नाव</span>
                        <span class="fw-bold text-dark">{{ $parent['parent_name'] ?? $student->guardian_name ?? 'पालक' }}</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">घरी नियमित वाचन व गृहअध्ययन करतो का?</span>
                        <span class="badge bg-success text-white">होय, नियमित करतो</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-secondary">डिजिटल खेळांमुळे अभ्यासातील गोडी वाढली का?</span>
                        <span class="badge bg-success text-white">होय, अत्यंत सकारात्मक प्रभाव</span>
                    </div>

                    <div class="p-3 rounded-3" style="background-color: #ecfeff; border-left: 4px solid #06b6d4;">
                        <div class="small fw-bold text-dark mb-1">पालकांचा प्रत्यक्ष अभिप्राय:</div>
                        <div class="small text-secondary">“{{ $parent['parent_remarks'] ?? 'घरी मोबाईलवर इतर व्हिडिओ पाहण्याऐवजी AppleMath खेळ आवडीने खेळतो. गणिताची गोडी निर्माण झाली आहे.' }}”</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Signatures Strip -->
    <div class="card portal-card p-4 border-0 shadow-sm rounded-4">
        <h4 class="h6 fw-bold text-muted text-uppercase mb-4">अधिकृत स्वाक्षरी व संमती (Signatures & Authentication)</h4>
        <div class="row text-center g-4">
            <div class="col-sm-3">
                <div class="border-top pt-2">
                    <div class="fw-bold text-dark small">{{ $student->user->name }}</div>
                    <div class="text-muted" style="font-size: 11px;">विद्यार्थी स्वाक्षरी</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="border-top pt-2">
                    <div class="fw-bold text-dark small">{{ $parent['parent_name'] ?? $student->guardian_name ?? 'पालक' }}</div>
                    <div class="text-muted" style="font-size: 11px;">पालक स्वाक्षरी</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="border-top pt-2">
                    <div class="fw-bold text-dark small">श्री. अमोल किसन हंकारे</div>
                    <div class="text-muted" style="font-size: 11px;">फेलो / वर्गशिक्षक</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="border-top pt-2">
                    <div class="fw-bold text-dark small">श्री. सोमनाथ वाळके सर</div>
                    <div class="text-muted" style="font-size: 11px;">मेंतॉर मार्गदर्शक / मुख्याध्यापक</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
