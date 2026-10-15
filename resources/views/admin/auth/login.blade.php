@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div class="card">
        <div class="brand" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(config('app.name'), 0, 1)) }}</div>
        <h1>{{ config('app.name') }} Admin</h1>
        <p class="sub">Sign in to manage enquiries.</p>

        <form method="POST" action="{{ route('admin.login.store') }}" data-lock novalidate>
            @csrf

            <label for="email">Email</label>
            <input
                class="f"
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                autofocus
                required
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            >
            @error('email')
                <p class="err-msg" id="email-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <div class="pwf">
                <input
                    class="f"
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                >
                <button class="ib" type="button" data-toggle-password="password" aria-label="Show password" aria-pressed="false">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <p class="err-msg" id="password-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
            @enderror

            <label class="chk" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Keep me signed in
            </label>

            <button class="btn pri block" type="submit">Sign in</button>
        </form>
    </div>
@endsection
