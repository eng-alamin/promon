<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Sign in') · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script>try{document.documentElement.dataset.theme=localStorage.getItem('theme')||(matchMedia('(prefers-color-scheme:dark)').matches?'glass':'light')}catch(e){}</script>
    <link href="{{ asset('assets/admin/css/admin.css') }}?v={{ filemtime(public_path('assets/admin/css/admin.css')) }}" rel="stylesheet">
</head>
<body class="auth-page">
    <a class="skip" href="#main">Skip to content</a>

    <button class="ib theme-corner" id="themeBtn" type="button" aria-label="Toggle dark mode"><i class="bi bi-moon-stars" aria-hidden="true"></i></button>

    <main class="auth" id="main">
        @yield('content')
    </main>

    <div id="toasts" role="status" aria-live="polite"></div>

    <script src="{{ asset('assets/admin/js/admin.js') }}?v={{ filemtime(public_path('assets/admin/js/admin.js')) }}"></script>
</body>
</html>
