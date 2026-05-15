@php
    $s = $widget->settings ?? [];
    $headline = trim((string) data_get($s, 'headline', ''));
    $sub = trim((string) data_get($s, 'subheadline', ''));
    $ctaLabel = trim((string) data_get($s, 'cta_label', ''));
    $ctaUrl = \App\Support\FormUrls::resolveCtaUrl(
        trim((string) data_get($s, 'cta_label', '')),
        (string) data_get($s, 'cta_url', '')
    );
    $anchor = trim((string) data_get($s, 'anchor_id', ''));
@endphp
<section @if ($anchor !== '') id="{{ e($anchor) }}" @endif class="surface cms-page-widget cmsw-teaser">
    @if (trim((string) $widget->title) !== '')
        <p class="cmsw-eyebrow">{{ $widget->title }}</p>
    @endif
    @if ($headline !== '')
        <h2 class="cmsw-teaser__headline">{{ $headline }}</h2>
    @endif
    @if ($sub !== '')
        <p class="cmsw-teaser__sub">{{ $sub }}</p>
    @endif
    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-teaser__body">{!! $widget->content !!}</div>
    @endif
    @if ($ctaLabel !== '' && $ctaUrl !== '')
        <p class="cmsw-teaser__cta">
            <a class="btn-cmsw-primary" href="{{ e($ctaUrl) }}">{{ e($ctaLabel) }} <span class="btn-arrow" aria-hidden="true">→</span></a>
        </p>
    @endif
</section>
