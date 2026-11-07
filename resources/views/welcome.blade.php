<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <section class="card portal-card overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3 text-bg-warning fw-bold small">
                            <i class="bi bi-award-fill"></i>
                            यशवंतराव चव्हाण सेंटर, मुंबई | शरद पवार इन्स्पायर फेलोशिप इन एज्युकेशन सन २०२६–२७
                        </div>
                        <h1 class="display-6 fw-bold portal-brand mb-2">डिजिटल खेळाच्या माध्यमातून शैक्षणिक प्रगती मापन</h1>
                        <p class="h5 text-primary fw-semibold mb-3">“खेळातून शिकूया, प्रगती मोजूया”</p>
                        <p class="lead mb-4 text-secondary">
                            फेलो: <strong>श्री. अमोल किसन हंकारे</strong> &nbsp;|&nbsp;
                            मार्गदर्शक: <strong>श्री. सोमनाथ वाळके</strong><br>
                            <strong>जिल्हा परिषद शाळा नं. २ कुची</strong>, ता. कवठेमहांकाळ, जि. सांगली (ID: SPFEDU-0415)
                        </p>
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <a class="btn btn-primary btn-lg px-4" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> पोर्टल प्रवेश (Login)
                            </a>
                            <a class="btn btn-success btn-lg px-4 fw-bold" href="{{ route('hpc.index') }}">
                                <i class="bi bi-person-badge-fill me-1"></i> 📊 समग्र प्रगती पत्रक (HPC ३६०°)
                            </a>
                            <a class="btn btn-warning btn-lg px-4 text-dark fw-bold" href="{{ route('games.index') }}">
                                <i class="bi bi-controller me-1"></i> 🎮 इयत्ता ४ थी खेळ
                            </a>
                            <a class="btn btn-outline-primary btn-lg px-4" href="{{ route('fellowship.reports.index') }}">
                                <i class="bi bi-file-earmark-text me-1"></i> फेलोशिप अहवाल
                            </a>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded-4 p-3 h-100 bg-white shadow-sm">
                                    <i class="bi bi-controller fs-2 text-primary" aria-hidden="true"></i>
                                    <h2 class="h6 fw-bold mt-2 mb-1">डिजिटल खेळ (Games-led)</h2>
                                    <p class="mb-0 text-secondary small">AppleMath, अंकगाडी, संख्यारेषा व भाषा खेळांद्वारे रंजक व भीतीमुक्त अध्ययन.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-4 p-3 h-100 bg-white shadow-sm">
                                    <i class="bi bi-graph-up-arrow fs-2 text-success" aria-hidden="true"></i>
                                    <h2 class="h6 fw-bold mt-2 mb-1">अचूक प्रगती मापन (Evidence)</h2>
                                    <p class="mb-0 text-secondary small">विद्यार्थ्यांची अचूकता (Accuracy %) आणि लागणारा वेळ (Time Spent) यांचे स्वयंगती ट्रॅकिंग.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-4 p-3 h-100 bg-white shadow-sm">
                                    <i class="bi bi-file-earmark-pdf fs-2 text-danger" aria-hidden="true"></i>
                                    <h2 class="h6 fw-bold mt-2 mb-1">मासिक अहवाल (SPIF Reports)</h2>
                                    <p class="mb-0 text-secondary small">१२ महिन्यांचा अधिकृत कार्य अहवाल थेट वर्ड व PDF स्वरूपात तयार व एक्सपोर्ट.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
