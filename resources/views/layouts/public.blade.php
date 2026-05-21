@php
    $siteFooterWidgetForHeader = $footerWidgets->firstWhere('slug', \App\Models\Widget::SITE_FOOTER_SLUG);
    $headerFooterSettings = $siteFooterWidgetForHeader->settings ?? [];
    $headerFooterDefs = \App\Models\Widget::defaultSiteFooterSettings();
    $headerSocialIn = $headerFooterSettings['footer_social'] ?? $headerFooterDefs['footer_social'] ?? [];
    $headerSocials = [];
    $headerAllowedIcons = \App\Models\Widget::siteFooterSocialIconKeys();
    foreach ($headerSocialIn as $soc) {
        if (! is_array($soc)) {
            continue;
        }
        $url = trim((string) ($soc['url'] ?? ''));
        if ($url === '') {
            continue;
        }
        $icon = strtolower((string) ($soc['icon'] ?? 'link'));
        if ($icon === 'pinterest') {
            continue;
        }
        if (! in_array($icon, $headerAllowedIcons, true)) {
            $icon = 'link';
        }
        $label = \App\Models\Widget::decodeFooterText((string) ($soc['label'] ?? ''));
        if ($label === '') {
            $ariaByIcon = [
                'facebook' => 'Facebook',
                'instagram' => 'Instagram',
                'x' => 'X',
                'linkedin' => 'LinkedIn',
                'youtube' => 'YouTube',
                'link' => 'Link',
            ];
            $label = $ariaByIcon[$icon] ?? 'Link';
        }
        $headerSocials[] = [
            'icon' => $icon,
            'url' => $url,
            'label' => $label,
            'new_tab' => ! empty($soc['new_tab']),
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $siteName ?? config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --font: 'Montserrat', system-ui, -apple-system, sans-serif;
            --font-hero-display: 'Montserrat', system-ui, -apple-system, sans-serif;
            --font-hero-sans: 'Montserrat', system-ui, -apple-system, sans-serif;
            --color-menu-bar-bg: linear-gradient(90deg, #facc15 0%, #fb923c 55%, #f97316 100%);
            --color-menu-bar-text: rgba(28, 25, 23, 0.82);
            --color-menu-bar-text-hover: #ffffff;
            --color-menu-bar-border: rgba(28, 25, 23, 0.14);
            --color-menu-bar-hover-bg: #0d9488;
            --color-menu-bar-submenu-hover: #0f766e;
            --color-menu-bar-submenu-hover-bg: rgba(13, 148, 136, 0.12);
        }

        /* ── Refined Header ── */
        html { background: #ffffff; }

        .site-header {
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
            /* Span full viewport width so any scrollbar gutter is covered too */
            width: 100vw;
            margin-left: 0;
            margin-right: calc(100% - 100vw);
            background: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.04);
        }

        .menu-bar {
            width: 100%;
            display: block;
        }

        .header-inner {
            max-width: 1140px;
            padding: 0.65rem 1.25rem;
        }

        .brand-logo {
            max-height: 44px;
        }

        /* Header Social Media Links (top right) */
        .header-social-wrap {
            display: none;
        }

        @media (min-width: 768px) {
            .header-social-wrap {
                display: block;
            }
        }

        .header-social {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .header-social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            text-decoration: none;
            background: #ffffff;
            border: 1.5px solid rgba(15, 23, 42, 0.1);
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                        background-color 0.2s ease,
                        border-color 0.2s ease,
                        color 0.2s ease,
                        box-shadow 0.2s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .header-social-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: #ffffff !important;
            border-color: transparent;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.18);
        }

        /* Default: white pill with brand-colored icon. On hover: filled with brand color. */
        .header-social-btn--facebook { color: #1877f2; }
        .header-social-btn--facebook:hover { background-color: #1877f2; }

        .header-social-btn--instagram { color: #dc2743; }
        .header-social-btn--instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .header-social-btn--x { color: #000000; }
        .header-social-btn--x:hover { background-color: #000000; }

        .header-social-btn--pinterest { color: #bd081c; }
        .header-social-btn--pinterest:hover { background-color: #bd081c; }

        .header-social-btn--linkedin { color: #0a66c2; }
        .header-social-btn--linkedin:hover { background-color: #0a66c2; }

        .header-social-btn--youtube { color: #ff0000; }
        .header-social-btn--youtube:hover { background-color: #ff0000; }

        .header-social-btn--link { color: var(--color-primary); }
        .header-social-btn--link:hover { background-color: var(--color-primary); }

        .header-social-btn .site-footer__social-icon {
            width: 1.15rem;
            height: 1.15rem;
            flex-shrink: 0;
            display: block;
        }

        /* Site footer social buttons styling with brand colors */
        .site-footer__social-btn {
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), filter 0.2s ease, box-shadow 0.2s ease !important;
        }
        .site-footer__social-btn:hover {
            transform: translateY(-2px) scale(1.05);
            filter: brightness(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .site-footer__social-btn--facebook { background-color: #1877f2 !important; color: #ffffff !important; }
        .site-footer__social-btn--instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%) !important; color: #ffffff !important; }
        .site-footer__social-btn--x { background-color: #000000 !important; color: #ffffff !important; }
        .site-footer__social-btn--pinterest { background-color: #bd081c !important; color: #ffffff !important; }
        .site-footer__social-btn--linkedin { background-color: #0a66c2 !important; color: #ffffff !important; }
        .site-footer__social-btn--youtube { background-color: #ff0000 !important; color: #ffffff !important; }
        .site-footer__social-btn--link { background-color: var(--color-primary) !important; color: #ffffff !important; }

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
            background: rgba(13, 148, 136, 0.08);
        }

        /* Menu bar nav links - dark text on yellow/orange gradient */
        .menu-bar .nav-desktop a {
            color: var(--color-menu-bar-text);
            font-weight: 700;
            transition: background 0.22s ease, color 0.22s ease, box-shadow 0.22s ease;
        }

        .menu-bar .nav-desktop a:hover,
        .menu-bar .nav-desktop a:focus {
            color: #b45309;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.92) 0%, rgba(254, 215, 170, 0.75) 100%);
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(120, 53, 15, 0.18);
        }

        /* Keep the parent button styled the same way while its dropdown is open */
        .menu-bar .nav-dropdown-wrap:hover .nav-dropdown-trigger,
        .menu-bar .nav-dropdown-wrap:focus-within .nav-dropdown-trigger {
            color: #b45309;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.92) 0%, rgba(254, 215, 170, 0.75) 100%);
            box-shadow: 0 2px 10px rgba(120, 53, 15, 0.18);
        }

        /* ── Sub-menu dropdown: polished, brand-aligned ── */
        .menu-bar .nav-dropdown-wrap {
            position: relative;
        }

        .menu-bar .nav-dropdown {
            margin-top: 0.5rem;
            padding: 0.4rem;
            min-width: 14rem;
            background: #ffffff;
            border: 1px solid rgba(217, 119, 6, 0.18);
            border-radius: 12px;
            box-shadow:
                0 18px 40px rgba(120, 53, 15, 0.18),
                0 4px 12px rgba(120, 53, 15, 0.06);
            overflow: hidden;
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity 0.22s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.22s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
        }

        .menu-bar .nav-dropdown-wrap:hover .nav-dropdown,
        .menu-bar .nav-dropdown-wrap:focus-within .nav-dropdown {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Top accent bar — ties the dropdown panel into the menu bar palette */
        .menu-bar .nav-dropdown::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #facc15 0%, #fb923c 55%, #0d9488 100%);
            border-radius: 12px 12px 0 0;
        }

        /* Caret pointing up from the panel to the trigger */
        .menu-bar .nav-dropdown::after {
            content: "";
            position: absolute;
            top: -7px;
            left: 1.5rem;
            width: 12px;
            height: 12px;
            background: #ffffff;
            border-top: 1px solid rgba(217, 119, 6, 0.18);
            border-left: 1px solid rgba(217, 119, 6, 0.18);
            transform: rotate(45deg);
            border-top-left-radius: 2px;
        }

        /* Invisible hover bridge between the trigger and the panel — prevents the dropdown from closing when crossing the gap */
        .menu-bar .nav-dropdown-wrap::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.7rem;
            background: transparent;
            pointer-events: none;
        }

        .menu-bar .nav-dropdown-wrap:hover::after {
            pointer-events: auto;
        }

        .menu-bar .nav-dropdown li {
            position: relative;
        }

        .menu-bar .nav-dropdown a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            position: relative;
            padding: 0.65rem 0.9rem 0.65rem 1.15rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--color-text);
            background: transparent;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: none;
            transition: background-color 0.18s ease, color 0.18s ease, padding-left 0.22s ease;
        }

        /* Left-edge accent that slides in on hover */
        .menu-bar .nav-dropdown a::before {
            content: "";
            position: absolute;
            left: 0.4rem;
            top: 50%;
            width: 3px;
            height: 0;
            border-radius: 999px;
            background: linear-gradient(180deg, #d97706 0%, #f97316 100%);
            transform: translateY(-50%);
            transition: height 0.22s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Right-side chevron that appears on hover */
        .menu-bar .nav-dropdown a::after {
            content: "→";
            display: inline-block;
            font-size: 0.95rem;
            font-weight: 700;
            color: #d97706;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .menu-bar .nav-dropdown a:hover,
        .menu-bar .nav-dropdown a:focus {
            color: #b45309;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.85) 0%, rgba(254, 215, 170, 0.55) 100%);
            padding-left: 1.4rem;
            box-shadow: none;
            text-decoration: none;
        }

        .menu-bar .nav-dropdown a:hover::before,
        .menu-bar .nav-dropdown a:focus::before {
            height: 1.25rem;
        }

        .menu-bar .nav-dropdown a:hover::after,
        .menu-bar .nav-dropdown a:focus::after {
            opacity: 1;
            transform: translateX(0);
        }

        /* Reset the older first/last child radius overrides — uniform radius for every item */
        .menu-bar .nav-dropdown li:first-child a,
        .menu-bar .nav-dropdown li:last-child a {
            border-radius: 8px;
        }

        /* Dropdown trigger caret stays dark on the bright bar */
        .menu-bar .nav-dropdown-trigger::after {
            border-top-color: rgba(28, 25, 23, 0.6);
            transition: border-top-color 0.2s ease, transform 0.2s ease;
        }

        .menu-bar .nav-dropdown-wrap:hover .nav-dropdown-trigger::after,
        .menu-bar .nav-dropdown-wrap:focus-within .nav-dropdown-trigger::after {
            border-top-color: #b45309;
            transform: rotate(180deg);
        }

        .menu-bar {
            display: none;
        }

        @media (min-width: 768px) {
            .menu-bar {
                display: block;
                border-top: 1px solid var(--color-menu-bar-border);
                background: var(--color-menu-bar-bg);
                box-shadow: 0 4px 14px rgba(217, 119, 6, 0.32);
            }
            .menu-bar-inner {
                max-width: 1140px;
                margin: 0 auto;
                padding: 0.45rem 1.25rem;
            }
            .menu-bar-inner .nav-desktop {
                display: flex;
                justify-content: flex-end; /* Menu aligned to the right */
                gap: 0.25rem 1.25rem;
            }
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

        .menu-bar .nav-desktop .btn-ghost,
        .menu-bar .nav-desktop .btn-sm {
            border-color: var(--color-menu-bar-border);
            color: var(--color-menu-bar-text);
        }

        .menu-bar .nav-desktop .btn-ghost:hover {
            color: #b45309;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.92) 0%, rgba(254, 215, 170, 0.75) 100%);
            border-color: rgba(217, 119, 6, 0.35);
            box-shadow: 0 2px 10px rgba(120, 53, 15, 0.18);
        }

        /* ── Hero: immersive full-width banner ── */
        .mukmin-hero {
            position: relative;
            background:
                radial-gradient(ellipse 120% 90% at 50% -12%, rgba(230, 224, 216, 0.55) 0%, rgba(120, 108, 98, 0.12) 38%, transparent 64%),
                linear-gradient(162deg, var(--mukmin-hero-g1) 0%, var(--mukmin-hero-g2) 48%, var(--mukmin-hero-g3) 100%);
            color: #fff;
            min-height: min(45vh, 440px);
            overflow: hidden;
        }

        .mukmin-hero__photo {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 1;
            z-index: 0;
        }

        .mukmin-hero__gradient {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to top, rgba(15, 61, 44, 0.85) 0%, rgba(15, 61, 44, 0.5) 50%, rgba(0,0,0,0.15) 100%);
            opacity: 1;
            z-index: 1;
        }

        .mukmin-hero:has(.mukmin-hero__photo) .mukmin-hero__gradient {
            opacity: 1;
        }

        .mukmin-hero__vignette {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 100% 54% at 50% 0%, rgba(240, 235, 228, 0.12), transparent 54%),
                radial-gradient(ellipse 88% 72% at 50% 108%, rgba(15, 61, 44, 0.25), transparent 58%);
            z-index: 1;
        }

        .mukmin-hero:has(.mukmin-hero__photo) .mukmin-hero__vignette {
            background:
                radial-gradient(ellipse 96% 50% at 50% 0%, rgba(225, 218, 208, 0.12), transparent 52%),
                radial-gradient(ellipse 90% 76% at 50% 110%, rgba(15, 61, 44, 0.3), transparent 62%);
        }

        /* Side-panel now hidden — photo is full-bleed background */
        .mukmin-hero__side-panel {
            display: none;
        }

        .mukmin-hero__side-panel__img,
        .mukmin-hero__side-panel__scrim,
        .mukmin-hero__side-panel-origin {
            display: none;
        }

        /* Headline typography: white with gradient accent */
        .mukmin-hero__headline {
            color: #ffffff;
            font-family: var(--font-hero-display);
            font-size: clamp(2.2rem, 6vw, 3.8rem);
            font-weight: 800;
            text-shadow:
                0 2px 8px rgba(0, 0, 0, 0.25),
                0 0 40px rgba(13, 148, 136, 0.15);
        }

        /* CMS-body content inside hero (h1/h2/p rendered from widget content) */
        .mukmin-hero__inner {
            position: relative;
            z-index: 3;
        }

        .mukmin-hero__inner h1,
        .mukmin-hero__inner h2 {
            color: #ffffff !important;
            font-family: var(--font-hero-display);
            font-weight: 800;
            font-size: clamp(2.2rem, 6vw, 3.6rem);
            text-shadow:
                0 2px 8px rgba(0, 0, 0, 0.25),
                0 0 40px rgba(13, 148, 136, 0.15);
        }

        .mukmin-hero__inner em,
        .mukmin-hero__inner strong {
            background: linear-gradient(to right, #fde047, #fb923c);
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

        .mukmin-hero__cta {
            position: relative;
            z-index: 3;
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

            @if (count($headerSocials) > 0)
                <div class="header-social-wrap">
                    <ul class="header-social" aria-label="{{ __('Social media') }}">
                        @foreach ($headerSocials as $soc)
                            <li>
                                <a
                                    class="header-social-btn header-social-btn--{{ $soc['icon'] }}"
                                    href="{{ $soc['url'] }}"
                                    aria-label="{{ $soc['label'] }}"
                                    @if (! empty($soc['new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                                >
                                    @include('widgets.partials.site-footer-social-svg', ['icon' => $soc['icon']])
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

        <!-- Menu Bar at the top of the hero / content -->
        <div class="menu-bar">
            <div class="menu-bar-inner">
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
            </div>
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
                    <p class="site-footer__copy">&copy; 2026 Pertubuhan Gabungan Mukmin Nasional (PPM-019-10-15042026) - All Rights Reserved</p>
                </div>
            </div>
        @endif
    </footer>
</body>
</html>
