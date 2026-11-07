<x-layouts.app title="शरद पवार इन्स्पायर फेलोशिप - मासिक कार्य अहवाल केंद्र">
    <!-- HERO HEADER -->
    <div class="card portal-card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #0d47a1 0%, #1565c0 50%, #1e88e5 100%); color: white; border-radius: 12px;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(255, 255, 255, 0.15); font-size: 13px; font-weight: 600;">
                        <i class="bi bi-award-fill text-warning"></i>
                        यशवंतराव चव्हाण सेंटर, मुंबई | शिक्षण विकास मंच
                    </div>
                    <h1 class="h2 fw-bold text-white mb-2">शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन सन २०२६–२७</h1>
                    <p class="lead mb-3" style="color: #e3f2fd; font-size: 1.15rem;">
                        उपक्रम: <strong>“डिजिटल खेळाच्या माध्यमातून शैक्षणिक प्रगती मापन”</strong> (खेळातून शिकूया, प्रगती मोजूया)
                    </p>
                    <div class="d-flex flex-wrap gap-3 align-items-center text-white" style="font-size: 13.5px; opacity: 0.95;">
                        <span><i class="bi bi-person-fill me-1 text-warning"></i> फेलो: <strong>श्री. अमोल किसन हंकारे</strong></span>
                        <span>•</span>
                        <span><i class="bi bi-person-check-fill me-1 text-warning"></i> मेंटॉर: <strong>श्री. सोमनाथ वाळके</strong></span>
                        <span>•</span>
                        <span><i class="bi bi-building me-1 text-warning"></i> <strong>जि. प. शाळा नं. २ कुची</strong>, ता. कवठेमहांकाळ, जि. सांगली</span>
                        <span>•</span>
                        <span class="badge text-bg-warning text-dark px-2 py-1">ID: SPFEDU-0415</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 text-lg-end">
                    <a class="btn btn-warning btn-lg fw-bold shadow-sm" href="{{ route('fellowship.reports.show', 4) }}">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> माहे सप्टेंबर २०२६ अहवाल
                    </a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('reports.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> सामान्य अहवाल केंद्र
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK STATUS & ANNUAL STATS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card portal-card h-100 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <span class="text-secondary small">वार्षिक अहवाल प्रगती</span>
                    <div class="h3 fw-bold portal-brand mb-0">{{ $completedCount }} / {{ $totalMonths }} महिने</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPct }}%" aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="small text-muted">{{ $progressPct }}% पूर्ण</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card portal-card h-100 border-start border-4 border-success">
                <div class="card-body p-3">
                    <span class="text-secondary small">एकूण खेळलेली सत्रे</span>
                    <div class="h3 fw-bold text-success mb-0">{{ $totalSessions }}+</div>
                    <span class="small text-muted">AppleMath व गणितीय खेळ</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card portal-card h-100 border-start border-4 border-info">
                <div class="card-body p-3">
                    <span class="text-secondary small">सरासरी अचूकता (Accuracy)</span>
                    <div class="h3 fw-bold text-info mb-0">{{ $overallAccuracy }}%</div>
                    <span class="small text-muted">विद्यार्थ्यांच्या अचूकतेत सातत्यपूर्ण वाढ</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card portal-card h-100 border-start border-4 border-warning">
                <div class="card-body p-3">
                    <span class="text-secondary small">सहभागी विद्यार्थी</span>
                    <div class="h3 fw-bold text-warning-emphasis mb-0">१५ विद्यार्थी</div>
                    <span class="small text-muted">१००% FLN क्षमता संपादन गट</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CURRENT MONTH HIGHLIGHT (SEPTEMBER 2026) -->
    <div class="card portal-card mb-4 border-primary border-2 shadow-sm">
        <div class="card-header bg-white border-bottom p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge text-bg-primary fs-6">चालू महिना</span>
                <h2 class="h5 mb-0 fw-bold">माहे सप्टेंबर २०२६ - मासिक कार्य अहवाल</h2>
                <span class="badge text-bg-success">सादर केले (Submitted)</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ asset('reports/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.docx') }}" download>
                    <i class="bi bi-file-earmark-word-fill me-1 text-primary"></i> Word (.docx) डाउनलोड
                </a>
                <a class="btn btn-sm btn-outline-danger" href="{{ asset('reports/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.pdf') }}" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i> PDF पहा / डाउनलोड
                </a>
                <a class="btn btn-sm btn-primary" href="{{ route('fellowship.reports.print', 4) }}" target="_blank">
                    <i class="bi bi-printer-fill me-1"></i> A4 प्रिंट दृश्य
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-calendar-check me-1"></i> १ ला व २ रा आठवडा:</h6>
                    <p class="small text-secondary mb-0">
                        पोर्टलवरील AppleMath व शैक्षणिक खेळांद्वारे (संख्यारेषा उडी, स्थानिक किंमत घर, बेरीज-वजाबाकी सफर) नियमित सराव. अचूकता (Accuracy %) आणि लागणारा वेळ (Time Spent) यांचे सातत्यपूर्ण डिजिटल मापन.
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-trophy me-1"></i> ३ रा व ४ था आठवडा:</h6>
                    <p class="small text-secondary mb-0">
                        मूलभूत क्रियांमध्ये अडचणी येत असणाऱ्या ८ विद्यार्थ्यांसाठी विशेष उपचारात्मक (Remedial) सराव सत्रे. स्वयंगती शिक्षण (Self-paced Learning) व मराठी शब्द वाचन सराव.
                    </p>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="small">
                    <strong>डिजिटल सांख्यिकी:</strong> ७८ खेळ सत्रे पूर्ण · ८६.८% सरासरी अचूकता · १:२८ मि. सरासरी वेळ · १८ नवीन लेव्हल्स अनलॉक
                </div>
                <a class="btn btn-sm btn-outline-primary fw-semibold" href="{{ route('fellowship.reports.show', 4) }}">
                    सविस्तर अहवाल व मेंटॉर शेरा पहा <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- FILTER TABS & ALL 12 MONTHS GRID -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <h2 class="h4 mb-0 fw-bold portal-brand">वार्षिक नियोजन व १२ महिन्यांचे अहवाल (सन २०२६–२७)</h2>
        <div class="btn-group btn-group-sm" role="group">
            <a class="btn {{ ! $statusFilter ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('fellowship.reports.index') }}">सर्व महिने (१२)</a>
            <a class="btn {{ $statusFilter === 'approved' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('fellowship.reports.index', ['status' => 'approved']) }}">तपासलेले (Approved)</a>
            <a class="btn {{ $statusFilter === 'submitted' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('fellowship.reports.index', ['status' => 'submitted']) }}">सादर केलेले (Submitted)</a>
            <a class="btn {{ $statusFilter === 'draft' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('fellowship.reports.index', ['status' => 'draft']) }}">मसुदा (Draft)</a>
        </div>
    </div>

    <div class="row g-3 mb-5">
        @foreach ($reports as $report)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card portal-card h-100 shadow-sm border-0 position-relative">
                    <div class="card-header bg-white border-0 pt-3 px-3 pb-0 d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill text-bg-light border text-dark fw-bold">
                            महिना क्र. {{ $report->month_order }}
                        </span>
                        <span class="badge {{ $report->status_badge_class }}">
                            {{ $report->status_label_marathi }}
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <h3 class="h5 fw-bold mb-1">{{ $report->month_name_marathi }}</h3>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $report->report_date ? $report->report_date->format('d/m/Y') : 'नियोजित' }}
                            @if ($report->mentor_status === 'approved')
                                · <span class="text-success"><i class="bi bi-patch-check-fill"></i> मेंटॉर प्रमाणित</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="small fw-semibold text-primary mb-1">
                                <i class="bi bi-clock-history me-1"></i> १ ला व २ रा आठवडा:
                            </div>
                            <div class="small text-secondary text-truncate-2" style="font-size: 12px; min-height: 36px;">
                                {{ $report->week1_2_title }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="small fw-semibold text-primary mb-1">
                                <i class="bi bi-check2-circle me-1"></i> ३ रा व ४ था आठवडा:
                            </div>
                            <div class="small text-secondary text-truncate-2" style="font-size: 12px; min-height: 36px;">
                                {{ $report->week3_4_title }}
                            </div>
                        </div>

                        @if ($report->metrics_summary)
                            <div class="p-2 rounded bg-light small mb-3">
                                <strong>सत्र:</strong> {{ $report->metrics_summary['total_game_sessions'] ?? 0 }} · 
                                <strong>अचूकता:</strong> {{ $report->metrics_summary['average_accuracy'] ?? 0 }}% · 
                                <strong>विद्यार्थी:</strong> {{ $report->metrics_summary['active_students'] ?? 15 }}
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent border-0 p-3 pt-0 d-flex justify-content-between align-items-center gap-2">
                        <a class="btn btn-outline-primary btn-sm flex-grow-1" href="{{ route('fellowship.reports.show', $report) }}">
                            <i class="bi bi-eye me-1"></i> सविस्तर पहा
                        </a>
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('fellowship.reports.edit', $report) }}" title="संपादन करा">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('fellowship.reports.print', $report) }}" target="_blank" title="A4 प्रिंट">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- DIGITAL GAMES SHOWCASE BANNER -->
    <div class="card portal-card bg-light border-0 p-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge text-bg-primary mb-2">उपक्रमाचे मुख्य डिजिटल गेम्स साधन</span>
                <h3 class="h4 fw-bold mb-2">AppleMath & Marathi-Maths Digital Games</h3>
                <p class="text-secondary mb-0">
                    श्री. अमोल किसन हंकारे यांनी स्वतः विकसित केलेले <strong>AppleMath</strong> आणि या पोर्टलवरील मराठी आणि गणित अध्ययन खेळ हे या फेलोशिपचे मुख्य 'मूल्यमापन साधन' (Evaluation Tool) आहेत. यातून मिळणारा वेळ व अचूकतेचा अचूक डेटा विद्यार्थ्यांच्या पोर्टफोलिओमध्ये जतन केला जातो.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2 justify-content-lg-end">
                    <a class="btn btn-success fw-bold" href="{{ route('games.index') }}">
                        <i class="bi bi-controller me-1"></i> पोर्टलवरील खेळ खेळा
                    </a>
                    <a class="btn btn-outline-primary" href="https://games.techedu.in/applemath/" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i> AppleMath ऑनलाइन पहा
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
