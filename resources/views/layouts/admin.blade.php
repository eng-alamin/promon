<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script>try{document.documentElement.dataset.theme=localStorage.getItem('theme')||(matchMedia('(prefers-color-scheme:dark)').matches?'glass':'light')}catch(e){}</script>
    <link href="{{ asset('assets/admin/css/admin.css') }}?v={{ filemtime(public_path('assets/admin/css/admin.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <a class="skip" href="#main">Skip to content</a>

    <div class="shell">
        @include('admin.partials.rail')

        <div class="main">
            @include('admin.partials.topbar')

            <div class="body" id="main" role="main">
                @yield('content')
            </div>
        </div>
    </div>

    <div id="toasts" role="status" aria-live="polite"></div>

    @if (session('success'))
        <div hidden data-flash="{{ session('success') }}"></div>
    @endif

    <script src="{{ asset('assets/admin/js/admin.js') }}?v={{ filemtime(public_path('assets/admin/js/admin.js')) }}"></script>
    @stack('scripts')
</body>
</html>
