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
