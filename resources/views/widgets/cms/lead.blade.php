@php
    $s = $widget->settings ?? [];
    $variant = (string) data_get($s, 'variant', 'surface');
    if ($variant === '') {
        $variant = 'surface';
    }
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
    $ctas = data_get($s, 'ctas', []);
    if (! is_array($ctas)) {
        $ctas = [];
    }
    $hasImage = $resolvedImage !== '';
    $wrapClass = 'cms-page-widget cmsw-lead';
    if ($hasImage) {
        $wrapClass .= ' cmsw-lead--with-hero';
    } elseif ($variant === 'gradient') {
        $wrapClass .= ' cmsw-lead--gradient';
    } elseif ($variant === 'muted') {
        $wrapClass .= ' cmsw-lead--muted';
    } else {
        $wrapClass .= ' surface';
    }
@endphp
<section class="{{ $wrapClass }}">
    @if ($hasImage)
        {{-- Hero banner mode: image background with overlaid text --}}
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
            @if (trim((string) $widget->content) !== '')
                <div class="cms-body cmsw-lead__body">{!! $widget->content !!}</div>
            @endif
            @if (count($ctas) > 0)
                <div class="cmsw-lead__actions">
                    @foreach ($ctas as $cta)
                        @php
                            if (! is_array($cta)) {
                                continue;
                            }
                            $label = trim((string) ($cta['label'] ?? ''));
                            $url = trim((string) ($cta['url'] ?? ''));
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
        </div>
    @else
        {{-- Text-only mode (no image) --}}
        <div class="cmsw-lead__content">
            @if ($eyebrow !== '')
                <p class="cmsw-eyebrow">{{ $eyebrow }}</p>
            @endif
            @if ($headline !== '')
                <h2 class="cmsw-lead__headline">{{ $headline }}</h2>
            @elseif (trim((string) $widget->title) !== '')
                <h2 class="cmsw-lead__headline">{{ $widget->title }}</h2>
            @endif
            @if ($sub !== '')
                <p class="cmsw-lead__sub">{{ $sub }}</p>
            @endif
            @if (trim((string) $widget->content) !== '')
                <div class="cms-body cmsw-lead__body">{!! $widget->content !!}</div>
            @endif
            @if (count($ctas) > 0)
                <div class="cmsw-lead__actions">
                    @foreach ($ctas as $cta)
                        @php
                            if (! is_array($cta)) {
                                continue;
                            }
                            $label = trim((string) ($cta['label'] ?? ''));
                            $url = trim((string) ($cta['url'] ?? ''));
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
        </div>
    @endif
</section>
