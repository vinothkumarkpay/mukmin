<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $siteName ?? config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500..800;1,9..144,500&family=Source+Sans+3:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* ── Refined Header ── */
        .site-header {
            position: relative;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.04);
        }

        .site-header::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .header-inner {
            max-width: 1140px;
            padding: 0.65rem 1.25rem;
        }

        .brand-logo {
            max-height: 44px;
        }

        /* Desktop nav links */
        .nav-desktop {
            gap: 0.15rem 0.5rem;
        }

        .nav-desktop a {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--color-text);
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .nav-desktop a:hover {
            color: var(--color-primary);
            background: rgba(31, 107, 74, 0.06);
        }

        /* Dropdown polish */
        .nav-dropdown {
            padding: 0.5rem;
            min-width: 13rem;
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.08);
        }

        .nav-dropdown a {
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            font-size: 0.88rem;
        }

        .nav-dropdown li:first-child a { border-radius: 8px; }
        .nav-dropdown li:last-child a  { border-radius: 8px; }

        /* Caret refinement */
        .nav-dropdown-trigger::after {
            border-top-width: 4px;
            border-left-width: 3.5px;
            border-right-width: 3.5px;
            opacity: 0.4;
            transition: opacity 0.2s ease;
        }

        .nav-dropdown-wrap:hover .nav-dropdown-trigger::after {
            opacity: 0.7;
        }

        /* Ghost button refinement */
        .nav-desktop .btn-ghost,
        .nav-desktop .btn-sm {
            font-size: 0.82rem;
            padding: 0.3rem 0.6rem;
            border-color: rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* ── Hero: mud / earth tones (uses widget CSS variables) ── */
        .mukmin-hero {
            background:
                radial-gradient(ellipse 120% 90% at 50% -12%, rgba(230, 224, 216, 0.55) 0%, rgba(120, 108, 98, 0.12) 38%, transparent 64%),
                linear-gradient(162deg, var(--mukmin-hero-g1) 0%, var(--mukmin-hero-g2) 48%, var(--mukmin-hero-g3) 100%);
            color: #fff;
            min-height: min(78vh, 740px);
        }

        .mukmin-hero__photo {
            opacity: 0.78;
            filter: saturate(0.95) contrast(1.05) brightness(0.98);
        }

        .mukmin-hero__gradient {
            background:
                linear-gradient(198deg, rgba(210, 200, 190, 0.22) 0%, rgba(95, 85, 75, 0.12) 36%, transparent 68%),
                linear-gradient(160deg, var(--mukmin-hero-g1) 0%, var(--mukmin-hero-g2) 48%, var(--mukmin-hero-g3) 100%);
            opacity: var(--mukmin-hero-overlay);
        }

        .mukmin-hero:has(.mukmin-hero__photo) .mukmin-hero__gradient {
            opacity: clamp(0.32, calc(var(--mukmin-hero-overlay) * 0.82), 0.52);
        }

        .mukmin-hero__vignette {
            background:
                radial-gradient(ellipse 100% 54% at 50% 0%, rgba(240, 235, 228, 0.28), transparent 54%),
                radial-gradient(ellipse 88% 72% at 50% 108%, rgba(45, 42, 38, 0.12), transparent 58%);
        }

        .mukmin-hero:has(.mukmin-hero__photo) .mukmin-hero__vignette {
            background:
                radial-gradient(ellipse 96% 50% at 50% 0%, rgba(225, 218, 208, 0.28), transparent 52%),
                linear-gradient(to bottom, rgba(75, 68, 60, 0.14) 0%, transparent 48%),
                radial-gradient(ellipse 90% 76% at 50% 110%, rgba(25, 22, 18, 0.18), transparent 62%);
        }

        .mukmin-hero__side-panel {
            position: absolute;
            z-index: 2;
            left: var(--mukmin-side-panel-left, calc(50% + clamp(4.5rem, 11.5vw, 10rem)));
            right: clamp(0.45rem, 1.75vw, 1.35rem);
            width: auto;
            top: clamp(3rem, 7.5vh, 5rem);
            height: min(68vh, 34rem);
            margin: 0;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 1.15rem;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.86;
            box-shadow:
                0 1.25rem 2.25rem rgba(18, 14, 12, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.14);
        }

        .mukmin-hero__side-panel__img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center 42%;
            opacity: 0.97;
            filter: saturate(1.02) contrast(1.04) brightness(1.02);
        }

        .mukmin-hero__side-panel__scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(195deg, rgba(236, 253, 245, 0.18) 0%, transparent 50%, rgba(15, 60, 52, 0.14) 100%);
            opacity: 0.4;
        }

        .mukmin-hero__side-panel-origin {
            display: inline;
            width: 0;
            height: 0;
            margin: 0;
            padding: 0;
            vertical-align: baseline;
            pointer-events: none;
        }

        @media (max-width: 899px) {
            .mukmin-hero__side-panel {
                display: none;
            }
        }

        /* Headline typography: white with gradient accent */
        .mukmin-hero__headline {
            color: #ffffff;
            font-size: clamp(2.2rem, 6vw, 3.8rem);
            font-weight: 800;
            text-shadow:
                0 2px 8px rgba(0, 0, 0, 0.25),
                0 0 40px rgba(13, 148, 136, 0.15);
        }

        /* CMS-body content inside hero (h1/h2/p rendered from widget content) */
        .mukmin-hero__inner h1,
        .mukmin-hero__inner h2 {
            color: #ffffff !important;
            font-weight: 800;
            font-size: clamp(2.2rem, 6vw, 3.6rem);
            text-shadow:
                0 2px 8px rgba(0, 0, 0, 0.25),
                0 0 40px rgba(13, 148, 136, 0.15);
        }

        .mukmin-hero__inner em,
        .mukmin-hero__inner strong {
            background: linear-gradient(to right, #5eead4, #a7f3d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-style: normal;
        }

        .mukmin-hero__inner p {
            color: rgba(255, 255, 255, 0.88) !important;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .mukmin-hero__sub {
            color: rgba(255, 255, 255, 0.85);
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        /* Carousel cards: premium floating effect */
        .mukmin-hero-carousel__viewport {
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.3);
        }

        .mukmin-hero-carousel__card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.35s ease;
        }

        .mukmin-hero-carousel__card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
        }

        .mukmin-hero-carousel__slide {
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <input type="checkbox" id="public-mobile-nav" class="nav-sheet-toggle" tabindex="-1">

            <label for="public-mobile-nav" class="nav-burger" tabindex="0" aria-controls="public-mobile-nav-panel">
                <span class="nav-burger-lines" aria-hidden="true"></span>
                <span class="sr-only">{{ __('Open menu') }}</span>
            </label>

            <a class="brand" href="{{ route('home') }}">
                @if ($siteLogoUrl)
                    <img class="brand-logo" src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" loading="lazy" decoding="async">
                    <span class="brand-text sr-only">{{ $siteName }}</span>
                @else
                    <span class="brand-text">{{ $siteName }}</span>
                @endif
            </a>

            <nav class="nav-desktop" aria-label="{{ __('Primary') }}">
                @foreach ($headerMenuItems as $item)
                    @if ($item->children->isNotEmpty())
                        <div class="nav-dropdown-wrap">
                            <a href="{{ $item->url }}" class="nav-dropdown-trigger"
                               @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                            <ul class="nav-dropdown" role="list">
                                @foreach ($item->children as $child)
                                    <li role="none">
                                        <a href="{{ $child->url }}" role="menuitem"
                                           @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a href="{{ $item->url }}"
                           @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                    @endif
                @endforeach
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post" style="display:inline;margin:0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-ghost" style="margin-left:.25rem">{{ __('Log out') }}</button>
                    </form>
                @endauth
            </nav>

            <label for="public-mobile-nav" class="nav-scrim" aria-label="{{ __('Close menu') }}">
                <span class="nav-scrim-dismiss" aria-hidden="true">&times;</span>
            </label>

            <aside class="nav-sheet" id="public-mobile-nav-panel" aria-label="{{ __('Menu') }}">
                <div class="nav-sheet-lead">
                    <div class="nav-sheet-lead-main">
                        <span class="nav-sheet-kicker">{{ __('Browse') }}</span>
                        <span class="nav-sheet-site">{{ $siteName }}</span>
                    </div>
                    <div class="nav-sheet-lead-actions">
                        @auth
                            <span class="nav-sheet-user">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 22) }}</span>
                        @endauth
                    </div>
                </div>
                <nav class="nav-sheet-links">
                    @foreach ($headerMenuItems as $item)
                        @if ($item->children->isNotEmpty())
                            <div class="nav-sheet-group" role="group" aria-label="{{ $item->label }}">
                                <span class="nav-sheet-group-label">{{ $item->label }}</span>
                                <a href="{{ $item->url }}" class="nav-sheet-group-overview"
                                   @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ __('Overview') }}</a>
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->url }}" class="nav-sheet-sublink"
                                       @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $child->label }}</a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ $item->url }}"
                               @if($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $item->label }}</a>
                        @endif
                    @endforeach
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        @endif
                        <form action="{{ route('logout') }}" method="post" class="nav-sheet-logout">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-ghost" style="width:100%">{{ __('Log out') }}</button>
                        </form>
                    @endauth
                </nav>
            </aside>
        </div>
    </header>

    @if(isset($headerWidgets) && $headerWidgets->isNotEmpty())
        <div class="main-wrap widget-zone" style="padding-top:.75rem;padding-bottom:0">
            @foreach ($headerWidgets as $widget)
                <div class="surface widget">
                    <h2 class="page-title" style="font-size:1rem;margin-bottom:.5rem">{{ $widget->title }}</h2>
                    <div class="cms-body">{!! $widget->content !!}</div>
                </div>
            @endforeach
        </div>
    @endif

    <main class="main-wrap">
        @yield('content')
    </main>

    @php
        $siteFooterWidget = $footerWidgets->firstWhere('slug', \App\Models\Widget::SITE_FOOTER_SLUG);
        $otherFooterWidgets = $footerWidgets->filter(function ($w) {
            return $w->slug !== \App\Models\Widget::SITE_FOOTER_SLUG;
        });
    @endphp
    <footer class="site-footer">
        @if ($siteFooterWidget)
            @include('widgets.site-footer', [
                'widget' => $siteFooterWidget,
                'siteName' => $siteName,
                'otherFooterWidgets' => $otherFooterWidgets,
            ])
        @else
            <div class="site-footer__bar">
                <div class="site-footer__bar-inner">
                    <p class="site-footer__copy">&copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. {{ __('Add an active widget with slug “site-footer” in the footer zone to edit links.') }}</p>
                </div>
            </div>
        @endif
    </footer>
</body>
</html>
