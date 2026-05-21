@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultSiteFooterSettings();
    $suffix = \App\Models\Widget::decodeFooterText((string) ($s['footer_copyright_suffix'] ?? $defs['footer_copyright_suffix'] ?? ''));
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
@if ($otherFooterWidgets->isNotEmpty())
    <div class="site-footer__nav">
        <div class="site-footer__nav-inner">
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
        </div>
    </div>
@endif
<div class="site-footer__bar">
    <div class="site-footer__bar-inner">
        <p class="site-footer__copy">
            &copy; 2026 Pertubuhan Gabungan Mukmin Nasional (PPM-019-10-15042026) - All Rights Reserved
        </p>
    </div>
</div>
