<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Log in') }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
<div class="login-card surface">
    <h1 class="page-title" style="margin-top:0">{{ __('Admin login') }}</h1>
    <p class="muted">{{ __('Sign in to manage portal content.') }}</p>

    <form method="post" action="{{ route('login') }}" class="form-grid" style="margin-top:1rem">
        @csrf
        <label class="field">
            <span>{{ __('Email') }}</span>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Password') }}</span>
            <input type="password" name="password" required autocomplete="current-password">
            @error('password')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="check">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span>{{ __('Remember me') }}</span>
        </label>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
            <a class="btn btn-ghost" href="{{ route('home') }}">{{ __('Back to site') }}</a>
        </div>
    </form>
</div>
</body>
</html>
