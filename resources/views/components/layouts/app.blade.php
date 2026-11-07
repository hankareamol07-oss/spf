<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top" aria-label="Primary navigation">
        <div class="container">
            <a class="navbar-brand portal-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <i class="bi bi-award-fill text-warning fs-5" aria-hidden="true"></i>
                <div>
                    <div>शरद पवार इन्स्पायर फेलोशिप पोर्टल</div>
                    <div class="small text-secondary fw-normal" style="font-size: 11px;">जि. प. शाळा नं. २ कुची, सांगली</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNavigation" aria-controls="portalNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="portalNavigation">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    @if (auth()->user()->hasRole(\App\Enums\RoleCode::SuperAdmin, \App\Enums\RoleCode::SchoolAdmin, \App\Enums\RoleCode::Mentor))
                        <a class="nav-link fw-bold text-primary active" href="{{ route('fellowship.reports.index') }}">
                            <i class="bi bi-file-earmark-text-fill text-warning me-1"></i>फेलोशिप अहवाल
                        </a>
                        <a class="nav-link fw-bold text-success" href="{{ route('hpc.index') }}">
                            <i class="bi bi-person-badge-fill text-success me-1"></i>HPC ३६०° पत्रक
                        </a>
                    @endif
                    <a class="nav-link fw-semibold text-primary" href="{{ route('games.index') }}">
                        <i class="bi bi-controller text-danger me-1"></i>इयत्ता ४ थी खेळ
                    </a>
                    @if (auth()->user()->hasRole(\App\Enums\RoleCode::SuperAdmin))
                        <a class="nav-link" href="{{ route('schools.index') }}">शाळा</a>
                        <a class="nav-link" href="{{ route('subjects.index') }}">अभ्यासक्रम</a>
                    @endif
                    @if (auth()->user()->hasRole(\App\Enums\RoleCode::SchoolAdmin))
                        <a class="nav-link" href="{{ route('students.index') }}">विद्यार्थी</a>
                        <a class="nav-link" href="{{ route('mentors.index') }}">मार्गदर्शक</a>
                        <a class="nav-link" href="{{ route('school-classes.index') }}">वर्ग</a>
                    @endif
                    @if (auth()->user()->hasRole(\App\Enums\RoleCode::SuperAdmin, \App\Enums\RoleCode::SchoolAdmin, \App\Enums\RoleCode::Mentor))
                        <a class="nav-link" href="{{ route('activities.index') }}">उपक्रम</a>
                        <a class="nav-link" href="{{ route('tests.index') }}">चाचण्या</a>
                    @endif
                    @if (auth()->user()->hasRole(\App\Enums\RoleCode::Student))
                        <a class="nav-link" href="{{ route('practice.index') }}">सराव</a>
                        <a class="nav-link" href="{{ route('games.index') }}">खेळ</a>
                        <a class="nav-link" href="{{ route('simulations.index') }}">अनुकरणे</a>
                        <a class="nav-link" href="{{ route('assessments.index') }}">चाचण्या</a>
                        <a class="nav-link text-success fw-bold" href="{{ route('hpc.my') }}">
                            <i class="bi bi-star-fill text-warning me-1"></i>माझे HPC पत्रक
                        </a>
                        <a class="nav-link" href="{{ route('portfolio.mine') }}">पोर्टफोलिओ</a>
                    @endif
                    <a class="nav-link" href="{{ route('reports.index') }}">सर्व अहवाल</a>
                    <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                        सूचना
                        @if ($unreadNotificationCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger">
                                {{ min(99, $unreadNotificationCount) }}
                                <span class="visually-hidden">न वाचलेल्या सूचना</span>
                            </span>
                        @endif
                    </a>
                    <span class="navbar-text">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>बाहेर पडा
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="container py-4">
        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif
        {{ $slot }}
    </main>
    @stack('scripts')
</body>
</html>
