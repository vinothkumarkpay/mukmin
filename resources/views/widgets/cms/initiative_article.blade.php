@php
    $s = $widget->settings ?? [];
    $eyebrow = trim((string) data_get($s, 'eyebrow', ''));
    $headline = trim((string) data_get($s, 'headline', ''));
    $note = trim((string) data_get($s, 'note', ''));
    $ctaLabel = trim((string) data_get($s, 'cta_label', ''));
    $ctaUrl = \App\Support\FormUrls::resolveCtaUrl(
        trim((string) data_get($s, 'cta_label', '')),
        (string) data_get($s, 'cta_url', '')
    );
    $ctaNewTab = (bool) data_get($s, 'cta_new_tab', false);
@endphp
<section class="surface cms-page-widget cmsw-article">
    @if ($eyebrow !== '')
        <p class="cmsw-eyebrow">{{ $eyebrow }}</p>
    @endif
    @if ($headline !== '')
        <h2 class="cmsw-article__headline">{{ $headline }}</h2>
    @elseif (trim((string) $widget->title) !== '')
        <h2 class="cmsw-article__headline">{{ $widget->title }}</h2>
    @endif
    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-article__body">{!! $widget->content !!}</div>
    @endif
    @if ($note !== '')
        <p class="cmsw-article__note muted">{{ $note }}</p>
    @endif
    @if ($ctaLabel !== '' && $ctaUrl !== '')
        <p class="cmsw-article__cta">
            <a class="btn-cmsw-primary" href="{{ e($ctaUrl) }}" @if ($ctaNewTab) target="_blank" rel="noopener noreferrer" @endif>{{ e($ctaLabel) }} <span class="btn-arrow" aria-hidden="true">→</span></a>
        </p>
    @endif
</section>
