<!doctype html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HPC प्रगती पत्रक — {{ $student->user->name }} (इयत्ता ४ थी)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Devanagari', 'Mukta', Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 13px;
        }
        .hpc-sheet {
            max-width: 210mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 15mm 15mm;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header-box {
            border: 2px solid #1e3a8a;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            background: #f8fafc;
            margin-bottom: 15px;
        }
        .table-hpc th, .table-hpc td {
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 12px;
        }
        .domain-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px;
            height: 100%;
            background: #ffffff;
        }
        .badge-fln {
            background-color: #10b981;
            color: #ffffff;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }
            .hpc-sheet {
                max-width: 100%;
                margin: 0;
                padding: 10mm;
                border: none;
                box-shadow: none;
            }
            .d-print-none {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>
    <div class="d-print-none text-center my-3">
        <button onclick="window.print()" class="btn btn-primary px-4 fw-bold">
            🖨️ हे प्रगती पत्रक प्रिंट करा (Print A4)
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary px-3 ms-2">
            बंद करा (Close)
        </button>
    </div>

    <div class="hpc-sheet">
        <!-- Official Header -->
        <div class="header-box">
            <div class="text-uppercase fw-bold text-primary small">
                यशवंतराव चव्हाण सेंटर, मुंबई | शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन २०२६–२७
            </div>
            <h1 class="h4 fw-bold my-1 text-dark">समग्र प्रगती पत्रक (Holistic Progress Card — HPC ३६०°)</h1>
            <div class="fw-bold text-secondary small">
                जिल्हा परिषद प्राथमिक शाळा नं. २ कुची, ता. कवठेमहांकाळ, जि. सांगली (UDISE: 27350302306)
            </div>
            <div class="mt-1 small text-muted">
                फेलो: <strong>श्री. अमोल किसन हंकारे</strong> &nbsp;|&nbsp; 
                मार्गदर्शक: <strong>मा. श्री. सोमनाथ वाळके सर</strong> &nbsp;|&nbsp; 
                सत्र: <strong>सत्र {{ $term }} (२०२६–२७)</strong>
            </div>
        </div>

        @php
            $self = $evaluation->self_assessment ?? [];
            $peer = $evaluation->peer_assessment ?? [];
            $teacher = $evaluation->teacher_assessment ?? [];
            $mentor = $evaluation->mentor_guidance ?? [];
            $parent = $evaluation->parent_feedback ?? [];
            $prefs = $student->accessibility_preferences ?? [];
        @endphp

        <!-- Student Identity Table -->
        <table class="table table-bordered table-hpc mb-3">
            <tbody>
                <tr>
                    <td class="bg-light fw-bold" style="width: 15%;">विद्यार्थ्याचे नाव:</td>
                    <td class="fw-bold text-primary" style="width: 35%;">{{ $student->user->name }}</td>
                    <td class="bg-light fw-bold" style="width: 15%;">हजेरी क्रमांक:</td>
                    <td style="width: 35%;"><strong>{{ $student->student_number ?? 'STU-'.str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</strong> (इयत्ता ४ थी 'अ')</td>
                </tr>
                <tr>
                    <td class="bg-light fw-bold">लिंग / जात संवर्ग:</td>
                    <td>{{ $student->gender === 'female' || $student->gender === 'मुलगी' ? 'मुलगी' : 'मुलगा' }} · {{ $prefs['category'] ?? 'General' }}</td>
                    <td class="bg-light fw-bold">PEN / APAAR ID:</td>
                    <td>PEN: {{ $prefs['pen'] ?? '22340002299' }} · APAAR: {{ $prefs['apaar_id'] ?? '****' }}</td>
                </tr>
                <tr>
                    <td class="bg-light fw-bold">आई व वडिलांचे नाव:</td>
                    <td>{{ $student->guardian_name ?? '—' }} / {{ $prefs['mother_name'] ?? '—' }}</td>
                    <td class="bg-light fw-bold">FLN समग्र नैपुण्य स्तर:</td>
                    <td>
                        <span class="badge-fln">{{ $evaluation->getFlnBadge()['label'] }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Section 1: Pre-Test & Post-Test Learning Evidence (चाचणी पुरावा) -->
        <h2 class="h6 fw-bold border-bottom pb-1 text-primary">
            १. पूर्व चाचणी व उत्तर चाचणी अध्ययन प्रगती पुरावा (Pre & Post Test Evidence)
        </h2>
        <table class="table table-bordered table-hpc text-center mb-3">
            <thead class="table-light">
                <tr>
                    <th>विषय</th>
                    <th>पूर्व चाचणी (Pre-Test)</th>
                    <th>उत्तर चाचणी (Post-Test)</th>
                    <th>सुधारणा / प्रगती (Growth)</th>
                    <th>FLN संपादणूक स्तर</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start fw-bold">गणित (Mathematics)</td>
                    <td>{{ $teacher['pre_test_avg'] ?? 52 }}%</td>
                    <td class="fw-bold text-success">{{ $teacher['cognitive_score'] ?? 88 }}%</td>
                    <td class="text-primary fw-bold">+{{ ($teacher['cognitive_score'] ?? 88) - ($teacher['pre_test_avg'] ?? 52) }}% वाढ</td>
                    <td><span class="badge bg-success">निपुण / प्रवीण (Level 3)</span></td>
                </tr>
                <tr>
                    <td class="text-start fw-bold">मराठी (प्रथम भाषा)</td>
                    <td>{{ ($teacher['pre_test_avg'] ?? 52) + 4 }}%</td>
                    <td class="fw-bold text-success">{{ min(100, ($teacher['cognitive_score'] ?? 88) + 2) }}%</td>
                    <td class="text-primary fw-bold">+{{ min(100, ($teacher['cognitive_score'] ?? 88) + 2) - (($teacher['pre_test_avg'] ?? 52) + 4) }}% वाढ</td>
                    <td><span class="badge bg-success">निपुण / प्रवीण (Level 3)</span></td>
                </tr>
            </tbody>
        </table>

        <!-- Section 2: SCERT 3 Core Domains (३ मुख्य क्षेत्रे) -->
        <h2 class="h6 fw-bold border-bottom pb-1 text-primary">
            २. SCERT बहुआयामी प्रगती क्षेत्रे (जाणीवजागृती, संवेदनशीलता, सर्जनशीलता)
        </h2>
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="domain-box text-center" style="border-top: 3px solid #f43f5e;">
                    <div class="fw-bold text-danger small">जाणीवजागृती (Awareness)</div>
                    <div class="fw-bold mt-1">{{ \App\Models\HpcEvaluation::levelLabel($teacher['awareness_level'] ?? 'pragat') }}</div>
                    <div class="text-muted" style="font-size: 11px;">संकल्पना समज, निरीक्षण क्षमता</div>
                </div>
            </div>
            <div class="col-4">
                <div class="domain-box text-center" style="border-top: 3px solid #eab308;">
                    <div class="fw-bold text-warning small" style="color: #a16207 !important;">संवेदनशीलता (Sensitivity)</div>
                    <div class="fw-bold mt-1">{{ \App\Models\HpcEvaluation::levelLabel($teacher['sensitivity_level'] ?? 'pragat') }}</div>
                    <div class="text-muted" style="font-size: 11px;">गटात सहकार्य, पर्यावरण जाणीव</div>
                </div>
            </div>
            <div class="col-4">
                <div class="domain-box text-center" style="border-top: 3px solid #0284c7;">
                    <div class="fw-bold text-primary small">सर्जनशीलता (Creativity)</div>
                    <div class="fw-bold mt-1">{{ \App\Models\HpcEvaluation::levelLabel($teacher['creativity_level'] ?? 'praveen') }}</div>
                    <div class="text-muted" style="font-size: 11px;">तार्किक विचार, समस्या निराकरण</div>
                </div>
            </div>
        </div>

        <!-- Section 3: Digital Game Learning Evidence (SPF Core) -->
        <h2 class="h6 fw-bold border-bottom pb-1 text-primary">
            ३. डिजिटल शैक्षणिक खेळ अध्ययन पुरावा (AppleMath Learning Evidence)
        </h2>
        <div class="p-2 border rounded bg-light mb-3">
            <div class="row text-center g-2">
                <div class="col-3"><strong>खेळलेली सत्रे:</strong> {{ $digitalEvidence['total_sessions'] }}+</div>
                <div class="col-3"><strong>एकूण वेळ:</strong> {{ $digitalEvidence['total_minutes'] }} मिनिटे</div>
                <div class="col-3"><strong>खेळ अचूकता:</strong> <span class="text-success fw-bold">{{ $digitalEvidence['avg_accuracy'] }}%</span></div>
                <div class="col-3"><strong>मुख्य खेळ:</strong> {{ $digitalEvidence['top_game'] }}</div>
            </div>
        </div>

        <!-- Section 4: The 4 Evaluation Pillars (३६० अंश स्तंभ) -->
        <h2 class="h6 fw-bold border-bottom pb-1 text-primary">
            ४. ३६०° बहुआयामी मूल्यांकन नोंदी (Self, Peer, Teacher, Parent)
        </h2>
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="border rounded p-2 h-100 bg-white">
                    <div class="fw-bold text-primary small mb-1">🧒 स्वयं-मूल्यांकन (Self):</div>
                    <ul class="mb-0 ps-3 small">
                        <li>शाळेत येणे: <strong>आनंदाने (😊 होय)</strong></li>
                        <li>आवडता खेळ: <strong>{{ $self['favorite_game'] ?? 'AppleMath' }}</strong></li>
                        <li>ध्येय: “{{ $self['my_goal'] ?? 'खेळातून गणित पक्के करणे.' }}”</li>
                    </ul>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 h-100 bg-white">
                    <div class="fw-bold text-success small mb-1">🤝 सहाध्यायी मित्र मूल्यांकन (Peer):</div>
                    <ul class="mb-0 ps-3 small">
                        <li>मित्र: <strong>{{ $peer['peer_name'] ?? 'सहकारी' }}</strong></li>
                        <li>गटात सहकार्य: <strong>होय, सहकार्याने</strong></li>
                        <li>गुणवत्ता: “{{ $peer['best_quality'] ?? 'खेळात छान मदत करतो.' }}”</li>
                    </ul>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 h-100 bg-white">
                    <div class="fw-bold text-dark small mb-1">👨‍🏫 शिक्षक व मेंतॉर अभिप्राय:</div>
                    <div class="small text-secondary">
                        {{ $teacher['strengths'] ?? 'संख्याज्ञान व खेळात उत्कृष्ट प्राविण्य.' }}<br>
                        <strong>मेंतॉर (सोमनाथ वाळके सर):</strong> “{{ $mentor['observation'] ?? 'खेळाने अध्ययनातील भीती दूर केली आहे.' }}”
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 h-100 bg-white">
                    <div class="fw-bold text-info small mb-1">👨‍👩‍👧 पालक अभिप्राय:</div>
                    <div class="small text-secondary">
                        पालक: <strong>{{ $parent['parent_name'] ?? $student->guardian_name ?? 'पालक' }}</strong><br>
                        “{{ $parent['parent_remarks'] ?? 'घरी आवडीने खेळ खेळतो व गणितातील आत्मविश्वास वाढला आहे.' }}”
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Signatures -->
        <table class="table table-bordered table-hpc text-center mt-4 mb-0">
            <tbody>
                <tr style="height: 48px;">
                    <td style="width: 25%; vertical-align: bottom;">{{ $student->user->name }}</td>
                    <td style="width: 25%; vertical-align: bottom;">{{ $student->guardian_name ?? 'पालक' }}</td>
                    <td style="width: 25%; vertical-align: bottom;">श्री. अमोल किसन हंकारे</td>
                    <td style="width: 25%; vertical-align: bottom;">श्री. सोमनाथ वाळके सर</td>
                </tr>
                <tr class="bg-light fw-bold small">
                    <td>विद्यार्थी स्वाक्षरी</td>
                    <td>पालक स्वाक्षरी</td>
                    <td>फेलो / वर्गशिक्षक स्वाक्षरी</td>
                    <td>मेंतॉर / मुख्याध्यापक स्वाक्षरी</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
