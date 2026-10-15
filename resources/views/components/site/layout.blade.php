@php
    $home = route('site.home');
    $links = [
        ['label' => 'About', 'href' => $home . '#about', 'key' => 'about'],
        ['label' => 'Products', 'href' => $home . '#products', 'key' => 'products'],
        ['label' => 'Wholesale & Export', 'href' => $home . '#services', 'key' => 'services'],
        ['label' => 'How it works', 'href' => $home . '#how', 'key' => 'how'],
        ['label' => 'Payment', 'href' => $home . '#payment', 'key' => 'payment'],
        ['label' => 'Contact', 'href' => $home . '#contact', 'key' => 'contact'],
    ];
    $pageTitle = $title !== '' ? $title . ' | ' . $company : $company . ' | UK Products to Bangladesh';
    $waUrl = strlen($waDigits) >= 8 ? 'https://wa.me/' . $waDigits : null;
@endphp
<!DOCTYPE html>
<html lang="en-GB">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $pageTitle }}</title>
  @if ($description !== '')
    <meta name="description" content="{{ $description }}">
    <meta property="og:description" content="{{ $description }}">
  @endif
  <meta property="og:title" content="{{ $pageTitle }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $company }}">
  <meta property="og:image" content="{{ asset('assets/site/img/logo.jpeg') }}">
  <meta name="theme-color" content="#071240">
  <link rel="canonical" href="{{ url()->current() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/site/img/logo-small.png') }}">
  @unless (app()->environment('production'))
    <meta name="robots" content="noindex, nofollow">
  @endunless
  <link rel="stylesheet" href="{{ asset('assets/site/css/promon.css') }}">
  <script src="{{ asset('assets/site/js/promon.js') }}" defer></script>
</head>
<body>
  <a href="#main" class="skip">Skip to main content</a>

  <header class="site-header">
    <div class="wrap">
      <a href="{{ $home }}" class="brand" aria-label="{{ $company }} home">
        <img src="{{ asset('assets/site/img/logo-small.png') }}" width="52" height="52" alt="{{ $company }} logo">
        <span><b>Promon</b><small>Limited</small></span>
      </a>
      <nav class="nav" aria-label="Main">
        @foreach ($links as $link)
          <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
        @endforeach
        <a href="{{ route('site.enquiry') }}" class="btn btn--gold btn--sm" @if ($nav === 'enquiry') aria-current="page" @endif>Enquire</a>
      </nav>
      <button type="button" class="menu-btn" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
    <nav id="mobile-menu" class="mobile-nav" data-menu aria-label="Mobile">
      @foreach ($links as $link)
        <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
      @endforeach
      <a href="{{ route('site.enquiry') }}" style="color: var(--gold-soft)">Enquire</a>
    </nav>
  </header>

  <main id="main">{{ $slot }}</main>

  <footer class="site-footer">
    <div class="wrap">
      <p class="tag">{{ $company }} &middot; <i>Bringing You the Best</i></p>
      <small>&copy; {{ date('Y') }} {{ $company }} &middot; Quality &middot; Trust &middot; Worldwide Connection</small>
    </div>
  </footer>

  @if ($waUrl)
    <a href="{{ $waUrl }}" class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp (opens in a new tab)">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.9 7L4 20l1.2-4.6A8 8 0 1 1 21 12z"/></svg>
    </a>
  @endif
</body>
</html>
