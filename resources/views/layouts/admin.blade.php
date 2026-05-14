<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Admin')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar admin-sidebar-desktop" aria-label="{{ __('Admin navigation') }}">
        <div class="logo">{{ config('app.name') }}</div>
        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
        <div class="nav-section">{{ __('Content') }}</div>
        <a href="{{ route('admin.site-configuration.edit') }}">{{ __('Site configuration') }}</a>
        <a href="{{ route('admin.menu-items.index') }}">{{ __('Menu items') }}</a>
        <a href="{{ route('admin.cms-pages.index') }}">{{ __('CMS pages') }}</a>
        <a href="{{ route('admin.widgets.index') }}">{{ __('Widgets') }}</a>
        <div class="nav-section">{{ __('Site') }}</div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">{{ __('View website') }}</a>
        <form action="{{ route('logout') }}" method="post" style="margin-top:1rem">
            @csrf
            <button type="submit" class="btn btn-sm btn-ghost" style="width:100%;color:inherit;border-color:rgba(255,255,255,.35)">{{ __('Log out') }}</button>
        </form>
    </aside>

    <div class="admin-body">
        <div class="admin-topbar">
            <details class="admin-nav-drawer admin-mobile-nav">
                <summary>{{ __('Menu') }}</summary>
                <div class="stack">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    <a href="{{ route('admin.site-configuration.edit') }}">{{ __('Site configuration') }}</a>
                    <a href="{{ route('admin.menu-items.index') }}">{{ __('Menu items') }}</a>
                    <a href="{{ route('admin.cms-pages.index') }}">{{ __('CMS pages') }}</a>
                    <a href="{{ route('admin.widgets.index') }}">{{ __('Widgets') }}</a>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">{{ __('View website') }}</a>
                </div>
            </details>
            <div style="font-weight:700">{{ $headerTitle ?? __('Admin') }}</div>
            <div></div>
        </div>

        <div class="admin-content">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
