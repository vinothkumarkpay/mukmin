@php
    $s = $widget->settings ?? [];
    $eyebrow = trim((string) data_get($s, 'eyebrow', ''));
    $headline = trim((string) data_get($s, 'headline', ''));
    $sub = trim((string) data_get($s, 'subheadline', ''));
    $imageUrl = trim((string) data_get($s, 'image_url', ''));
    $imagePath = trim((string) data_get($s, 'image_path', ''));
    $resolvedImage = '';
    if ($imagePath !== '' && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
        $resolvedImage = \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath);
    } elseif ($imageUrl !== '') {
        $resolvedImage = $imageUrl;
    }
    $pathway = trim((string) data_get($s, 'pathway', ''));
    $support = trim((string) data_get($s, 'support_line', ''));
    $ctas = data_get($s, 'ctas', []);
    if (! is_array($ctas)) {
        $ctas = [];
    }
    $hasImage = $resolvedImage !== '';
    $wrapClass = 'cms-page-widget cmsw-impact-intro';
    if ($hasImage) {
        $wrapClass .= ' cmsw-lead cmsw-lead--with-hero';
    } else {
        $wrapClass .= ' surface';
    }
@endphp
<section class="{{ $wrapClass }}">
    @if ($hasImage)
        <div class="cmsw-lead__hero">
            <img class="cmsw-lead__hero-image" src="{{ e($resolvedImage) }}" alt="{{ e($headline ?: $widget->title) }}" loading="lazy">
            <div class="cmsw-lead__hero-overlay"></div>
            <div class="cmsw-lead__hero-content">
                @if ($eyebrow !== '')
                    <p class="cmsw-eyebrow cmsw-lead__hero-eyebrow">{{ $eyebrow }}</p>
                @endif
                @if ($headline !== '')
                    <h2 class="cmsw-lead__hero-headline">{{ $headline }}</h2>
                @elseif (trim((string) $widget->title) !== '')
                    <h2 class="cmsw-lead__hero-headline">{{ $widget->title }}</h2>
                @endif
                @if ($sub !== '')
                    <p class="cmsw-lead__hero-sub">{{ $sub }}</p>
                @endif
            </div>
        </div>
        <div class="cmsw-lead__body-wrap">
    @else
        @if ($eyebrow !== '')
            <p class="cmsw-eyebrow">{{ $eyebrow }}</p>
        @endif
        @if ($headline !== '')
            <h2 class="cmsw-impact-intro__headline">{{ $headline }}</h2>
        @elseif (trim((string) $widget->title) !== '')
            <h2 class="cmsw-impact-intro__headline">{{ $widget->title }}</h2>
        @endif
        @if ($sub !== '')
            <p class="cmsw-impact-intro__sub">{{ $sub }}</p>
        @endif
    @endif

    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-impact-intro__body">{!! $widget->content !!}</div>
    @endif
    @if ($pathway !== '')
        <p class="cmsw-impact-intro__pathway" aria-label="{{ __('Impact pathway') }}">{{ $pathway }}</p>
    @endif
    @if ($support !== '')
        <p class="cmsw-impact-intro__support">{{ $support }}</p>
    @endif
    @if (count($ctas) > 0)
        <div class="cmsw-impact-intro__actions">
            @foreach ($ctas as $cta)
                @php
                    if (! is_array($cta)) {
                        continue;
                    }
                    $label = trim((string) ($cta['label'] ?? ''));
                    $url = \App\Support\FormUrls::resolveCtaUrl($label, (string) ($cta['url'] ?? ''));
                    if ($label === '' || $url === '') {
                        continue;
                    }
                    $style = strtolower((string) ($cta['style'] ?? 'primary'));
                    $btnClass = $style === 'ghost' ? 'btn-cmsw-ghost' : 'btn-cmsw-primary';
                    $nt = ! empty($cta['new_tab']);
                @endphp
                <a class="{{ $btnClass }}" href="{{ e($url) }}" @if ($nt) target="_blank" rel="noopener noreferrer" @endif>{{ e($label) }} <span class="btn-arrow" aria-hidden="true">→</span></a>
            @endforeach
        </div>
    @endif
    @if ($hasImage)
        </div>
    @endif
</section>
