@php
    use Illuminate\Support\Str;

    $user = auth()->user();
@endphp

<header class="top">
    <h1>@yield('title', 'Dashboard')</h1>

    <button class="ib" id="themeBtn" type="button" aria-label="Toggle dark mode"><i class="bi bi-moon-stars" aria-hidden="true"></i></button>

    <div class="who">
        <span class="av" aria-hidden="true">{{ Str::upper(Str::substr($user->name, 0, 1)) }}</span>
        <div>
            {{ $user->name }}
            <small>{{ $user->role->label() }}</small>
        </div>
    </div>
</header>
