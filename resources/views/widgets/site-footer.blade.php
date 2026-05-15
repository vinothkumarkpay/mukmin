@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultSiteFooterSettings();
    $suffix = \App\Models\Widget::decodeFooterText((string) ($s['footer_copyright_suffix'] ?? $defs['footer_copyright_suffix'] ?? ''));
    $colsIn = $s['footer_columns'] ?? $defs['footer_columns'] ?? [];
    $columns = [];
    foreach ($colsIn as $col) {
        if (! is_array($col)) {
            continue;
        }
        $title = \App\Models\Widget::decodeFooterText((string) ($col['title'] ?? ''));
        $links = [];
        foreach (($col['links'] ?? []) as $link) {
            if (! is_array($link)) {
                continue;
            }
            $lab = \App\Models\Widget::decodeFooterText((string) ($link['label'] ?? ''));
            $url = \App\Support\FormUrls::resolveCtaUrl($lab, (string) ($link['url'] ?? ''));
            if ($lab === '' || $url === '') {
                continue;
            }
            $links[] = [
                'label' => $lab,
                'url' => $url,
                'new_tab' => ! empty($link['new_tab']),
            ];
        }
        if ($title !== '' || count($links) > 0) {
            $columns[] = ['title' => $title, 'links' => $links];
        }
    }
    $socialIn = $s['footer_social'] ?? $defs['footer_social'] ?? [];
    $socials = [];
    $allowedIcons = \App\Models\Widget::siteFooterSocialIconKeys();
    foreach ($socialIn as $soc) {
        if (! is_array($soc)) {
            continue;
        }
        $url = trim((string) ($soc['url'] ?? ''));
        if ($url === '') {
            continue;
        }
        $icon = strtolower((string) ($soc['icon'] ?? 'link'));
        if (! in_array($icon, $allowedIcons, true)) {
            $icon = 'link';
        }
        $label = \App\Models\Widget::decodeFooterText((string) ($soc['label'] ?? ''));
        if ($label === '') {
            $ariaByIcon = [
                'facebook' => 'Facebook',
                'instagram' => 'Instagram',
                'x' => 'X',
                'pinterest' => 'Pinterest',
                'linkedin' => 'LinkedIn',
                'youtube' => 'YouTube',
                'link' => 'Link',
            ];
            $label = $ariaByIcon[$icon] ?? 'Link';
        }
        $socials[] = [
            'icon' => $icon,
            'url' => $url,
            'label' => $label,
            'new_tab' => ! empty($soc['new_tab']),
        ];
    }
    $otherFooterWidgets = $otherFooterWidgets ?? collect();
@endphp
<div class="site-footer__nav">
    <div class="site-footer__nav-inner">
        @if (count($columns) > 0)
            <nav class="site-footer__columns" aria-label="{{ __('Footer links') }}">
                @foreach ($columns as $col)
                    <div class="site-footer__col">
                        @if ($col['title'] !== '')
                            <h2 class="site-footer__heading">{{ $col['title'] }}</h2>
                        @endif
                        @if (count($col['links']) > 0)
                            <ul class="site-footer__list">
                                @foreach ($col['links'] as $link)
                                    <li>
                                        <a
                                            href="{{ $link['url'] }}"
                                            @if (! empty($link['new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                                        >{{ $link['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </nav>
        @endif
        @if ($otherFooterWidgets->isNotEmpty())
            <div class="site-footer__widgets">
                @foreach ($otherFooterWidgets as $fw)
                    <div class="site-footer__widget-block">
                        @if (trim((string) $fw->title) !== '')
                            <strong class="site-footer__widget-title">{{ $fw->title }}</strong>
                        @endif
                        <div class="site-footer__widget-body cms-body">{!! $fw->content !!}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<div class="site-footer__bar">
    <div class="site-footer__bar-inner">
        <p class="site-footer__copy">
            &copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}@if ($suffix !== ''). {{ $suffix }}@endif
        </p>
        @if (count($socials) > 0)
            <ul class="site-footer__social" aria-label="{{ __('Social media') }}">
                @foreach ($socials as $soc)
                    <li>
                        <a
                            class="site-footer__social-btn"
                            href="{{ $soc['url'] }}"
                            aria-label="{{ $soc['label'] }}"
                            @if (! empty($soc['new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                        >
                            @include('widgets.partials.site-footer-social-svg', ['icon' => $soc['icon']])
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
