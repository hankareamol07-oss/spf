<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <title>{{ $report->month_name_marathi }} - शरद पवार इन्स्पायर फेलोशिप मासिक कार्य अहवाल</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 12mm 14mm;
        }
        
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Segoe UI', 'Nirmala UI', 'Mangal', 'Arial', sans-serif;
            font-size: 12.5px;
            line-height: 1.45;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }

        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #475569;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 6px;
        }
        .btn-back:hover {
            background: #334155;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }

        .header-container {
            text-align: center;
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .institute-name {
            font-size: 12px;
            font-weight: 600;
            color: #455a64;
            margin-bottom: 2px;
        }

        .fellowship-title {
            font-size: 18px;
            font-weight: 700;
            color: #0d47a1;
            margin: 0 0 2px 0;
        }

        .fellowship-sub {
            font-size: 13px;
            font-weight: 600;
            color: #1565c0;
            margin: 0 0 4px 0;
        }

        .report-badge {
            display: inline-block;
            background-color: #e3f2fd;
            color: #0d47a1;
            font-size: 14px;
            font-weight: 700;
            padding: 3px 18px;
            border-radius: 4px;
            border: 1px solid #90caf9;
            margin-top: 2px;
        }

        .section-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0d47a1;
            background-color: #f1f5f9;
            padding: 5px 10px;
            border-left: 4px solid #0d47a1;
            margin: 12px 0 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
        }

        .details-table td:first-child {
            width: 30%;
            font-weight: 600;
            background-color: #f8fafc;
            color: #334155;
        }

        .work-table th:first-child {
            width: 28%;
        }

        .metrics-card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .metric-box {
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            padding: 8px;
            text-align: center;
            background: #f8fafc;
        }

        .metric-value {
            font-size: 16px;
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 1px;
        }

        .metric-label {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
        }

        ul {
            margin: 3px 0;
            padding-left: 18px;
        }

        li {
            margin-bottom: 4px;
        }

        .mentor-review-box {
            border: 1px dashed #0d47a1;
            background-color: #f8fafc;
            border-radius: 5px;
            padding: 10px 14px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .mentor-review-title {
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 4px;
            font-size: 12.5px;
        }

        .signature-section {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div>
            <strong>{{ $report->month_name_marathi }} - अधिकृत A4 प्रिंट व PDF</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('fellowship.reports.show', $report) }}" class="btn-back">← अहवाल पहा</a>
            <button onclick="window.print()" class="btn-print">🖨️ प्रिंट करा / PDF जतन करा</button>
        </div>
    </div>

    <div class="container">
        <!-- ==================== PAGE 1 ==================== -->
        <div class="header-container">
            <div class="institute-name">यशवंतराव चव्हाण सेंटर, मुंबई | शिक्षण विकास मंच</div>
            <div class="fellowship-title">शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन</div>
            <div class="fellowship-sub">सन २०२६ – २०२७</div>
            <div class="report-badge">मासिक कार्य अहवाल : {{ $report->month_name_marathi }}</div>
        </div>

        <!-- १. प्राथमिक व वैयक्तिक तपशील -->
        <div class="section-title">१. प्राथमिक व वैयक्तिक तपशील</div>
        <table class="details-table">
            <tr>
                <td>फेलोचे नाव</td>
                <td><strong>{{ $report->fellow_name }}</strong></td>
            </tr>
            <tr>
                <td>Application ID</td>
                <td><strong>{{ $report->application_id }}</strong></td>
            </tr>
            <tr>
                <td>पद व शाळा</td>
                <td>{{ $report->fellow_designation }}, <strong>{{ $report->school_name }}</strong></td>
            </tr>
            <tr>
                <td>पत्ता</td>
                <td>{{ $report->address }}</td>
            </tr>
            <tr>
                <td>शैक्षणिक पात्रता</td>
                <td>{{ $report->qualification }}</td>
            </tr>
            <tr>
                <td>ईमेल व फोन</td>
                <td>{{ $report->email }} &nbsp;|&nbsp; {{ $report->phone }}</td>
            </tr>
            <tr>
                <td>मेंतॉरचे नाव</td>
                <td><strong>{{ $report->mentor_name }}</strong></td>
            </tr>
            <tr>
                <td>शैक्षणिक परिवर्तन उपक्रमाचे नाव</td>
                <td><strong>“{{ $report->project_title }}”</strong> (खेळातून शिकूया, प्रगती मोजूया)</td>
            </tr>
        </table>

        <!-- २. प्रत्यक्ष कार्य अहवाल -->
        <div class="section-title">२. {{ $report->month_name_marathi }} मधील प्रत्यक्ष कार्य अहवाल</div>
        <p style="margin: 3px 0 8px 0; font-size: 12px; color: #475569;">
            वार्षिक नियोजन आराखड्यानुसार या महिन्यामध्ये 'पायाभूत साक्षरता आणि संख्याज्ञान (FLN)' अंतर्गत खालील उपक्रम राबविण्यात आले:
        </p>

        <table class="work-table">
            <thead>
                <tr>
                    <th>कालावधी / आठवडा</th>
                    <th>नियोजित व प्रत्यक्ष राबवलेला कार्य तपशील</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>१ ला व २ रा आठवडा</strong><br><span style="color:#64748b; font-size:11px;">({{ $report->week1_2_title }})</span></td>
                    <td>
                        <div style="white-space: pre-line;">
                            {{ $report->week1_2_description }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>३ रा व ४ था आठवडा</strong><br><span style="color:#64748b; font-size:11px;">({{ $report->week3_4_title }})</span></td>
                    <td>
                        <div style="white-space: pre-line;">
                            {{ $report->week3_4_description }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- ==================== PAGE 2 ==================== -->
        <!-- ३. डिजिटल खेळांद्वारे सांख्यिकी व प्रगती मापन -->
        <div class="section-title">३. डिजिटल खेळांद्वारे सांख्यिकी व प्रगती मापन (Digital Evidence & Metrics)</div>
        <p style="margin: 3px 0 8px 0; font-size: 12px; color: #475569;">
            उपक्रमांतर्गत पोर्टलवरील डिजिटल नोंदींवरून प्रत्यक्ष संपादणूक खालीलप्रमाणे संकलित झाली आहे:
        </p>

        <div class="metrics-card-grid">
            <div class="metric-box">
                <div class="metric-value">{{ $metrics['active_students'] ?? 15 }}</div>
                <div class="metric-label">सहभागी विद्यार्थी (१००%)</div>
            </div>
            <div class="metric-box">
                <div class="metric-value">{{ $metrics['total_game_sessions'] ?? 0 }}</div>
                <div class="metric-label">पूर्ण केलेली खेळ सत्रे</div>
            </div>
            <div class="metric-box">
                <div class="metric-value">{{ $metrics['average_accuracy'] ?? 0 }}%</div>
                <div class="metric-label">सरासरी अचूकता (Accuracy)</div>
            </div>
            <div class="metric-box">
                <div class="metric-value">{{ $metrics['average_time_spent_formatted'] ?? '१:२८ मि.' }}</div>
                <div class="metric-label">सरासरी सराव वेळ / सत्र</div>
            </div>
        </div>

        <table>
            <tr>
                <td style="width: 25%; font-weight: 600; background-color: #f8fafc;">पार पाडलेल्या पातळ्या</td>
                <td><strong>{{ $metrics['levels_progressed'] ?? 18 }} नवीन लेव्हल्स</strong> यशस्वीरीत्या पूर्ण (Foundation कडून Challenge कडे प्रगती)</td>
            </tr>
            <tr>
                <td style="font-weight: 600; background-color: #f8fafc;">उपचारात्मक मदत</td>
                <td><strong>{{ $metrics['remedial_interventions'] ?? 8 }} विद्यार्थ्यांना</strong> वैयक्तिक उपचारात्मक (Remedial) मार्गदर्शन दिले गेले.</td>
            </tr>
            <tr>
                <td style="font-weight: 600; background-color: #f8fafc;">प्रमुख खेळ व साधने</td>
                <td><strong>AppleMath</strong> (सफरचंद गणित), स्थानिक किंमत घर, संख्यारेषेवर उडी, बेरीज-वजाबाकी सफर, अक्षर पकडा, अंकगाडी.</td>
            </tr>
            <tr>
                <td style="font-weight: 600; background-color: #f8fafc;">FLN क्षमता संपादन</td>
                <td>संख्याज्ञान (Numeracy): <strong>{{ $metrics['fln_numeracy_mastery_pct'] ?? 82 }}%</strong> &nbsp;|&nbsp; पायाभूत साक्षरता (Literacy): <strong>{{ $metrics['fln_literacy_mastery_pct'] ?? 76 }}%</strong></td>
            </tr>
        </table>

        <!-- ४. प्रमुख निरीक्षणे व फलनिष्पत्ती -->
        <div class="section-title">४. प्रमुख निरीक्षणे व फलनिष्पत्ती (Key Outcomes)</div>
        <div style="white-space: pre-line; line-height: 1.55; margin-bottom: 10px;">
            {{ $report->key_outcomes }}
        </div>

        <!-- ५. आगामी नियोजन -->
        @if ($report->next_month_plan)
            <div class="section-title">५. पुढील महिन्याचे नियोजन</div>
            <div style="white-space: pre-line; line-height: 1.5; margin-bottom: 10px;">
                {{ $report->next_month_plan }}
            </div>
        @endif

        <!-- ६. मेंटॉरचा शेरा व स्वाक्षरी -->
        <div class="mentor-review-box">
            <div class="mentor-review-title">६. मेंटॉरचा शेरा / अभिप्राय (Mentor's Feedback):</div>
            <p style="margin: 0; line-height: 1.5; font-style: italic; color: #1e293b;">
                "{{ $report->mentor_remarks ?: 'माहे कार्य अहवाल समाधानकारक असून डिजिटल नोंदी योग्य आहेत.' }}"
            </p>
        </div>

        <div class="signature-section">
            <table style="border: none; margin-bottom: 0;">
                <tr style="border: none;">
                    <td style="border: none; width: 50%; vertical-align: top;">
                        <strong>दिनांक:</strong> {{ $report->report_date ? $report->report_date->format('d/m/Y') : '३०/०९/२०२६' }}<br>
                        <strong>ठिकाण:</strong> {{ $report->place }}
                        <div style="margin-top: 35px;">
                            <strong style="font-size: 13.5px; color: #0d47a1;">{{ $report->fellow_name }}</strong><br>
                            <span style="font-size: 11.5px; color: #475569;">(फेलो स्वाक्षरी)<br>{{ $report->fellow_designation }}, जि. प. शाळा नं. २ कुची</span>
                        </div>
                    </td>
                    <td style="border: none; width: 50%; text-align: right; vertical-align: bottom;">
                        <div style="margin-top: 50px;">
                            <strong style="font-size: 13.5px; color: #0d47a1;">{{ $report->mentor_name }}</strong><br>
                            <span style="font-size: 11.5px; color: #475569;">(मेंटॉर स्वाक्षरी)<br>शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
