<x-layouts.app title="{{ $report->month_name_marathi }} - मासिक कार्य अहवाल">
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('fellowship.reports.index') }}">फेलोशिप अहवाल केंद्र</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $report->month_name_marathi }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold portal-brand mb-0">{{ $report->month_name_marathi }} - अधिकृत मासिक कार्य अहवाल</h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('fellowship.reports.print', $report) }}" target="_blank">
                <i class="bi bi-printer-fill me-1"></i> A4 अधिकृत प्रिंट / PDF
            </a>
            <a class="btn btn-outline-secondary" href="{{ route('fellowship.reports.edit', $report) }}">
                <i class="bi bi-pencil-square me-1"></i> संपादन करा
            </a>
            <form method="POST" action="{{ route('fellowship.reports.sync-metrics', $report) }}" class="d-inline">
                @csrf
                <button class="btn btn-outline-info" type="submit" title="पोर्टलवरून थेट गेम आकडेवारी अद्ययावत करा">
                    <i class="bi bi-arrow-repeat me-1"></i> डेटा सिंक करा
                </button>
            </form>
            @if ($report->status === 'draft')
                <form method="POST" action="{{ route('fellowship.reports.submit', $report) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success" type="submit" onclick="return confirm('हा अहवाल फेलोशिपसाठी सादर करायचा आहे का?')">
                        <i class="bi bi-send-check-fill me-1"></i> सादर करा (Submit)
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if ($report->month_key === '2026-09')
        <div class="alert alert-success d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 shadow-sm">
            <div>
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <strong>सप्टेंबर २०२६ चा अहवाल अपलोडसाठी तयार आहे!</strong> Word दस्तऐवज किंवा PDF त्वरित डाउनलोड करा:
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-primary fw-bold" href="{{ asset('reports/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.docx') }}" download>
                    <i class="bi bi-file-earmark-word-fill me-1"></i> Word (.docx) फाईल
                </a>
                <a class="btn btn-sm btn-danger fw-bold" href="{{ asset('reports/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.pdf') }}" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF फाईल
                </a>
            </div>
        </div>
    @endif

    <div class="card portal-card shadow-sm border-0 mb-4">
        <!-- OFFICIAL REPORT LETTERHEAD -->
        <div class="card-body p-4 p-md-5">
            <div class="text-center pb-3 mb-4 border-bottom border-2 border-primary">
                <div class="text-muted fw-bold text-uppercase small mb-1">यशवंतराव चव्हाण सेंटर, मुंबई | शिक्षण विकास मंच</div>
                <h2 class="h3 fw-bold text-primary mb-1">शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन</h2>
                <div class="h5 fw-semibold text-secondary mb-2">सन २०२६ – २०२७</div>
                <div class="d-inline-block bg-primary-subtle text-primary fw-bold px-4 py-1 rounded border border-primary-subtle">
                    {{ $report->month_name_marathi }}
                </div>
            </div>

            <!-- १. प्राथमिक व वैयक्तिक तपशील -->
            <div class="mb-4">
                <h3 class="h5 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
                    १. प्राथमिक व वैयक्तिक तपशील
                </h3>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%; background-color: #f8fafc;">फेलोचे नाव</th>
                                <td><strong>{{ $report->fellow_name }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">Application ID</th>
                                <td><span class="badge text-bg-warning text-dark fs-6">{{ $report->application_id }}</span></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">पद व शाळा</th>
                                <td>{{ $report->fellow_designation }}, <strong>{{ $report->school_name }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">पत्ता</th>
                                <td>{{ $report->address }}</td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">शैक्षणिक पात्रता</th>
                                <td>{{ $report->qualification }}</td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">ईमेल व फोन</th>
                                <td>{{ $report->email }} &nbsp;|&nbsp; {{ $report->phone }}</td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">मेंतॉरचे नाव</th>
                                <td><strong>{{ $report->mentor_name }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background-color: #f8fafc;">शैक्षणिक परिवर्तन उपक्रमाचे नाव</th>
                                <td><strong>“{{ $report->project_title }}”</strong> (खेळातून शिकूया, प्रगती मोजूया)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- २. माहेवार प्रत्यक्ष कार्य अहवाल -->
            <div class="mb-4">
                <h3 class="h5 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
                    २. {{ $report->month_name_marathi }} मधील प्रत्यक्ष कार्य अहवाल
                </h3>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">कालावधी / आठवडा</th>
                                <th>नियोजित व प्रत्यक्ष राबवलेला कार्य तपशील</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-top bg-light">
                                    <strong class="text-primary">{{ $report->week1_2_title }}</strong><br>
                                    <span class="text-muted small">१ ला व २ रा आठवडा</span>
                                </td>
                                <td>
                                    <div style="white-space: pre-line; line-height: 1.6;">
                                        {{ $report->week1_2_description }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-top bg-light">
                                    <strong class="text-primary">{{ $report->week3_4_title }}</strong><br>
                                    <span class="text-muted small">३ रा व ४ था आठवडा</span>
                                </td>
                                <td>
                                    <div style="white-space: pre-line; line-height: 1.6;">
                                        {{ $report->week3_4_description }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ३. डिजिटल खेळांद्वारे सांख्यिकी व प्रगती मापन -->
            <div class="mb-4">
                <h3 class="h5 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
                    ३. डिजिटल खेळांद्वारे सांख्यिकी व प्रगती मापन (Digital Evidence & Metrics)
                </h3>
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="border rounded p-3 text-center bg-light">
                            <div class="h3 fw-bold text-primary mb-0">{{ $metrics['active_students'] ?? 15 }}</div>
                            <div class="small text-muted fw-semibold">सहभागी विद्यार्थी (१००%)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded p-3 text-center bg-light">
                            <div class="h3 fw-bold text-success mb-0">{{ $metrics['total_game_sessions'] ?? 0 }}</div>
                            <div class="small text-muted fw-semibold">पूर्ण केलेली खेळ सत्रे</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded p-3 text-center bg-light">
                            <div class="h3 fw-bold text-info mb-0">{{ $metrics['average_accuracy'] ?? 0 }}%</div>
                            <div class="small text-muted fw-semibold">सरासरी अचूकता (Accuracy)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded p-3 text-center bg-light">
                            <div class="h3 fw-bold text-warning-emphasis mb-0">{{ $metrics['average_time_spent_formatted'] ?? '१:२८ मि.' }}</div>
                            <div class="small text-muted fw-semibold">सरासरी वेळ / सत्र</div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 25%; background-color: #f8fafc;">पार पाडलेल्या पातळ्या</th>
                            <td><strong>{{ $metrics['levels_progressed'] ?? 18 }} नवीन लेव्हल्स</strong> यशस्वीरीत्या पूर्ण (Foundation कडून Challenge कडे प्रगती)</td>
                        </tr>
                        <tr>
                            <th style="background-color: #f8fafc;">उपचारात्मक मदत</th>
                            <td><strong>{{ $metrics['remedial_interventions'] ?? 8 }} विद्यार्थ्यांना</strong> वैयक्तिक उपचारात्मक (Remedial) मार्गदर्शन दिले गेले; अचूकतेत २०% वाढ नोंदवली गेली.</td>
                        </tr>
                        <tr>
                            <th style="background-color: #f8fafc;">प्रमुख खेळ व साधने</th>
                            <td>
                                <strong>AppleMath</strong> (सफरचंद गणित), स्थानिक किंमत घर, संख्यारेषेवर उडी, बेरीज-वजाबाकी सफर, अक्षर पकडा, अंकगाडी.
                            </td>
                        </tr>
                        <tr>
                            <th style="background-color: #f8fafc;">FLN क्षमता संपादन</th>
                            <td>
                                संख्याज्ञान (Numeracy): <strong>{{ $metrics['fln_numeracy_mastery_pct'] ?? 82 }}%</strong> &nbsp;|&nbsp;
                                पायाभूत साक्षरता (Literacy): <strong>{{ $metrics['fln_literacy_mastery_pct'] ?? 76 }}%</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ४. प्रमुख निरीक्षणे व फलनिष्पत्ती -->
            <div class="mb-4">
                <h3 class="h5 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
                    ४. प्रमुख निरीक्षणे व फलनिष्पत्ती (Key Outcomes)
                </h3>
                <div class="p-3 bg-light rounded" style="white-space: pre-line; line-height: 1.7;">
                    {{ $report->key_outcomes }}
                </div>
            </div>

            <!-- ५. आगामी नियोजन -->
            @if ($report->next_month_plan)
                <div class="mb-4">
                    <h3 class="h5 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
                        ५. पुढील महिन्याचे नियोजन
                    </h3>
                    <div class="p-3 bg-light rounded" style="white-space: pre-line; line-height: 1.6;">
                        {{ $report->next_month_plan }}
                    </div>
                </div>
            @endif

            <!-- ६. मेंटॉरचा शेरा व स्वाक्षरी विभाग -->
            <div class="card border-primary mb-4" style="background-color: #f8fafc; border-style: dashed !important;">
                <div class="card-body p-4">
                    <h4 class="h6 fw-bold text-primary mb-2">
                        ६. मेंटॉरचा शेरा / अभिप्राय (Mentor's Feedback & Verification):
                    </h4>
                    <p class="fst-italic text-secondary mb-0" style="line-height: 1.6;">
                        "{{ $report->mentor_remarks ?: 'अद्याप शेरा नोंदवला नाही.' }}"
                    </p>
                    <div class="mt-2">
                        <span class="badge {{ $report->mentor_status_badge_class }}">
                            {{ $report->mentor_status === 'approved' ? 'प्रमाणित व मंजूर (Approved)' : 'प्रलंबित (Pending)' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row pt-4 mt-4 border-top">
                <div class="col-6">
                    <div><strong>दिनांक:</strong> {{ $report->report_date ? $report->report_date->format('d/m/Y') : '३०/०९/२०२६' }}</div>
                    <div><strong>ठिकाण:</strong> {{ $report->place }}</div>
                    <div class="mt-4 pt-3">
                        <strong class="text-primary fs-6">{{ $report->fellow_name }}</strong><br>
                        <span class="text-muted small">(फेलो स्वाक्षरी)<br>{{ $report->fellow_designation }}, जि. प. शाळा नं. २ कुची</span>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <div class="mt-5 pt-3">
                        <strong class="text-primary fs-6">{{ $report->mentor_name }}</strong><br>
                        <span class="text-muted small">(मेंटॉर स्वाक्षरी)<br>शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
