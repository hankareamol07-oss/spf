<a class="spf-skip" href="#student-main">मुख्य भागाकडे जा</a>
<header class="spf-app-header">
    <a class="spf-wordmark" href="{{ route('dashboard') }}">
        <span class="spf-brand-mark" aria-hidden="true"><i class="bi bi-compass-fill"></i></span>
        <span><strong>शिकूया<span class="spf-brand-dot">.</span></strong><small>शरद पवार इन्स्पायर फेलोशिप</small></span>
    </a>
    <nav class="spf-desktop-nav" aria-label="मुख्य विभाग">
        <a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard', 'student.dashboard')) aria-current="page" @endif>माझे जग</a>
        <a href="{{ route('dashboard') }}#learning-map">अध्ययन नकाशा</a>
        <a href="{{ route('games.index') }}" @if(request()->routeIs('games.*')) aria-current="page" @endif>खेळ</a>
        <a href="{{ route('practice.index') }}" @if(request()->routeIs('practice.*')) aria-current="page" @endif>सराव</a>
    </nav>
    <div class="spf-header-actions">
        <a class="spf-icon-link" href="{{ route('notifications.index') }}" aria-label="सूचना">
            <i class="bi bi-bell" aria-hidden="true"></i>
            @if ($unreadNotificationCount > 0)<span class="spf-notification-dot"><span class="visually-hidden">न वाचलेल्या सूचना</span></span>@endif
        </a>
        <details class="spf-account-menu">
            <summary aria-label="माझे खाते"><span class="spf-avatar" aria-hidden="true"><i class="bi bi-person-fill"></i></span><span class="spf-account-name">{{ auth()->user()->name }}</span><i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
            <nav aria-label="खाते आणि इतर विभाग">
                <strong>{{ auth()->user()->name }}</strong>
                <a href="{{ route('portfolio.mine') }}">माझे पोर्टफोलिओ</a>
                <a href="{{ route('assessments.index') }}">माझ्या चाचण्या</a>
                <a href="{{ route('simulations.index') }}">अनुकरणे</a>
                <a href="{{ route('hpc.my') }}">माझे HPC पत्रक</a>
                <a href="{{ route('reports.index') }}">प्रगती अहवाल</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">बाहेर पडा</button></form>
            </nav>
        </details>
    </div>
</header>
<main id="student-main" class="container spf-main" tabindex="-1">
    @if (session('status'))<div class="alert alert-success" role="status">{{ session('status') }}</div>@endif
    {{ $slot }}
</main>
<nav class="spf-bottom-nav" aria-label="विद्यार्थी विभाग">
    <a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard', 'student.dashboard')) aria-current="page" @endif><i class="bi bi-house-door-fill" aria-hidden="true"></i><span>माझे जग</span></a>
    <a href="{{ route('dashboard') }}#learning-map"><i class="bi bi-map-fill" aria-hidden="true"></i><span>नकाशा</span></a>
    <a href="{{ route('games.index') }}" @if(request()->routeIs('games.*')) aria-current="page" @endif><i class="bi bi-controller" aria-hidden="true"></i><span>खेळ</span></a>
    <a href="{{ route('dashboard') }}#student-badges"><i class="bi bi-trophy-fill" aria-hidden="true"></i><span>बक्षिसे</span></a>
    <a href="{{ route('portfolio.mine') }}" @if(request()->routeIs('portfolio.*')) aria-current="page" @endif><i class="bi bi-person-fill" aria-hidden="true"></i><span>मी</span></a>
</nav>
