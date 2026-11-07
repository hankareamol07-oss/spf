<x-layouts.app :title="'समग्र प्रगती पत्रक (HPC ३६०°) — इयत्ता ४ थी'">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2 text-bg-warning fw-bold small">
                <i class="bi bi-award-fill"></i>
                यशवंतराव चव्हाण सेंटर, मुंबई | शरद पवार इन्स्पायर फेलोशिप २०२६–२७
            </div>
            <h1 class="h2 portal-brand mb-1">समग्र प्रगती पत्रक (HPC ३६०°)</h1>
            <p class="text-secondary mb-0">
                इयत्ता ४ थी · जिल्हा परिषद शाळा नं. २ कुची (ID: SPFEDU-0415) · सत्र {{ $term }} ({{ $academicYear->name }})
            </p>
        </div>
        <div class="d-flex gap-2">
            <div class="btn-group" role="group" aria-label="Term selector">
                <a href="{{ route('hpc.index', ['term' => 1]) }}" class="btn btn-{{ $term == 1 ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-calendar3 me-1"></i>सत्र १ (पहिले सत्र)
                </a>
                <a href="{{ route('hpc.index', ['term' => 2]) }}" class="btn btn-{{ $term == 2 ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-calendar3 me-1"></i>सत्र २ (दुसरे सत्र)
                </a>
            </div>
            <form method="POST" action="{{ route('hpc.sync-all') }}" class="d-inline">
                @csrf
                <input type="hidden" name="term" value="{{ $term }}">
                <button type="submit" class="btn btn-warning fw-bold">
                    <i class="bi bi-arrow-repeat me-1"></i>🔄 चाचणीनुसार स्वयं-अपडेट
                </button>
            </form>
            <a href="{{ route('games.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-controller me-1"></i>शैक्षणिक खेळ
            </a>
        </div>
    </div>

    <!-- 360° Framework Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <span class="badge bg-light text-primary px-3 py-1 rounded-pill fw-bold mb-2">NEP 2020 & SCERT महाराष्ट्र मार्गदर्शक तत्त्वे</span>
                    <h2 class="h4 fw-bold mb-2">३६० अंश बहुआयामी मूल्यमापन रचना (Class 4 Holistic Evaluation)</h2>
                    <p class="mb-0 text-white-50 small">
                        विद्यार्थ्यांचे केवळ गुणांवर आधारित नव्हे, तर <strong>स्वयं-मूल्यांकन (Self)</strong>, <strong>सहाध्यायी मूल्यांकन (Peer)</strong>, 
                        <strong>शिक्षक व मेंतॉर मूल्यांकन (Teacher/Mentor)</strong>, <strong>पालक अभिप्राय (Parent)</strong> आणि 
                        <strong>डिजिटल शैक्षणिक खेळांचा वस्तुनिष्ठ पुरावा (AppleMath Evidence)</strong> या ५ स्तंभांवर आधारित सर्वंकष प्रगती मापन.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-inline-flex flex-column gap-2 text-start bg-white bg-opacity-10 p-3 rounded-4">
                        <div class="small text-white"><i class="bi bi-check-circle-fill text-warning me-2"></i>जाणीवजागृती (Awareness)</div>
                        <div class="small text-white"><i class="bi bi-check-circle-fill text-warning me-2"></i>संवेदनशीलता (Sensitivity)</div>
                        <div class="small text-white"><i class="bi bi-check-circle-fill text-warning me-2"></i>सर्जनशीलता (Creativity)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card portal-card h-100 p-3 border-start border-4 border-primary">
                <div class="text-secondary small fw-semibold">इयत्ता ४ थी एकूण विद्यार्थी</div>
                <div class="d-flex align-items-baseline gap-2 mt-2">
                    <span class="fs-2 fw-bold text-dark">{{ $totalStudents }}</span>
                    <span class="text-muted small">विद्यार्थी</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card portal-card h-100 p-3 border-start border-4 border-success">
                <div class="text-secondary small fw-semibold">३६०° मूल्यांकन पूर्ण</div>
                <div class="d-flex align-items-baseline gap-2 mt-2">
                    <span class="fs-2 fw-bold text-success">{{ $evaluatedCount }}</span>
                    <span class="badge bg-success-subtle text-success small">१००% पूर्ण</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card portal-card h-100 p-3 border-start border-4 border-info">
                <div class="text-secondary small fw-semibold">FLN निपुण / प्रवीण स्तर (L3)</div>
                <div class="d-flex align-items-baseline gap-2 mt-2">
                    <span class="fs-2 fw-bold text-info">{{ $proficientCount }}</span>
                    <span class="badge bg-info-subtle text-info small">उत्कृष्ट गती</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card portal-card h-100 p-3 border-start border-4 border-warning">
                <div class="text-secondary small fw-semibold">प्रगतीपथावर सराव स्तर (L2)</div>
                <div class="d-flex align-items-baseline gap-2 mt-2">
                    <span class="fs-2 fw-bold text-warning">{{ $progressingCount }}</span>
                    <span class="badge bg-warning-subtle text-warning small">सराव सुरू</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Roster Table -->
    <div class="card portal-card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
            <h2 class="h5 fw-bold mb-0">
                <i class="bi bi-people-fill text-primary me-2"></i>इयत्ता ४ थी विद्यार्थी यादी व मूल्यांकन स्थिती
            </h2>
            <span class="badge bg-primary rounded-pill px-3 py-2">वर्ग तुकडी: ४ थी 'अ'</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 80px;">हजेरी क्र.</th>
                        <th scope="col">विद्यार्थ्याचे नाव</th>
                        <th scope="col">लिंग</th>
                        <th scope="col">FLN नैपुण्य स्तर</th>
                        <th scope="col">३६०° क्षेत्र पातळी (SCERT)</th>
                        <th scope="col">खेळ पुरावा (AppleMath)</th>
                        <th scope="col" class="text-end" style="min-width: 220px;">कृती (Actions)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        @php
                            $eval = $student->latestHpcEvaluation;
                            $flnBadge = $eval?->getFlnBadge() ?? ['label' => 'नोंद आवश्यक', 'class' => 'bg-secondary text-white'];
                            $tAss = $eval?->teacher_assessment ?? [];
                        @endphp
                        <tr>
                            <td class="fw-bold text-secondary">
                                {{ $student->student_number ?? 'STU-'.str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $student->user->name }}</div>
                                <div class="small text-muted">पालक: {{ $student->guardian_name ?? '—' }}</div>
                            </td>
                            <td>
                                @if ($student->gender === 'female' || $student->gender === 'मुलगी')
                                    <span class="badge bg-danger-subtle text-danger">मुलगी</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">मुलगा</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $flnBadge['class'] }} px-2 py-1">
                                    {{ $flnBadge['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-light text-dark border" title="जाणीवजागृती">
                                        जाणीव: {{ \App\Models\HpcEvaluation::levelLabel($tAss['awareness_level'] ?? 'praveen') }}
                                    </span>
                                    <span class="badge bg-light text-dark border" title="संवेदनशीलता">
                                        संवेदन: {{ \App\Models\HpcEvaluation::levelLabel($tAss['sensitivity_level'] ?? 'praveen') }}
                                    </span>
                                    <span class="badge bg-light text-dark border" title="सर्जनशीलता">
                                        सर्जन: {{ \App\Models\HpcEvaluation::levelLabel($tAss['creativity_level'] ?? 'praveen') }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small text-success fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i>अचूकता: {{ $tAss['cognitive_score'] ?? 90 }}%
                                </div>
                                <div class="small text-muted">सफरचंद गणित, संख्यारेषा</div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('hpc.show', ['student' => $student, 'term' => $term]) }}" class="btn btn-outline-primary" title="प्रगती पत्रक पहा">
                                        <i class="bi bi-eye-fill me-1"></i>पत्रक
                                    </a>
                                    <a href="{{ route('hpc.edit', ['student' => $student, 'term' => $term]) }}" class="btn btn-outline-secondary" title="मूल्यांकन संपादन">
                                        <i class="bi bi-pencil-fill me-1"></i>नोंद
                                    </a>
                                    <a href="{{ route('hpc.print', ['student' => $student, 'term' => $term]) }}" target="_blank" class="btn btn-outline-dark" title="A4 प्रिंट">
                                        <i class="bi bi-printer-fill me-1"></i>छापा
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
