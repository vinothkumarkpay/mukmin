@php
    $s = $widget->settings ?? [];
    $headline = trim((string) data_get($s, 'headline', ''));
    $sub = trim((string) data_get($s, 'subheadline', ''));
    $imageUrl = trim((string) data_get($s, 'image_url', ''));
    $groups = data_get($s, 'groups', []);
    if (! is_array($groups)) {
        $groups = [];
    }
    $quote = trim((string) data_get($s, 'quote', ''));
    $ctas = data_get($s, 'ctas', []);
    if (! is_array($ctas)) {
        $ctas = [];
    }
    $hasImage = $imageUrl !== '';
@endphp
<section class="cms-page-widget cmsw-team {{ $hasImage ? 'cmsw-team--with-hero' : 'surface' }}">
    @if ($hasImage)
        <div class="cmsw-team__hero">
            <img class="cmsw-team__hero-image" src="{{ e($imageUrl) }}" alt="{{ e($headline ?: $widget->title) }}" loading="lazy">
            <div class="cmsw-team__hero-overlay"></div>
            <div class="cmsw-team__hero-content">
                @if ($headline !== '')
                    <h2 class="cmsw-team__hero-headline">{{ $headline }}</h2>
                @elseif (trim((string) $widget->title) !== '')
                    <h2 class="cmsw-team__hero-headline">{{ $widget->title }}</h2>
                @endif
                @if ($sub !== '')
                    <p class="cmsw-team__hero-sub">{{ $sub }}</p>
                @endif
            </div>
        </div>
        <div class="cmsw-team__body-wrap">
    @else
        @if ($headline !== '')
            <h2 class="cmsw-team__headline">{{ $headline }}</h2>
        @elseif (trim((string) $widget->title) !== '')
            <h2 class="cmsw-team__headline">{{ $widget->title }}</h2>
        @endif
        @if ($sub !== '')
            <p class="cmsw-team__sub">{{ $sub }}</p>
        @endif
    @endif

    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-team__intro">{!! $widget->content !!}</div>
    @endif

    @if (count($groups) > 0)
        <div class="cmsw-team__groups">
            @foreach ($groups as $g)
                @php
                    if (! is_array($g)) {
                        continue;
                    }
                    $gt = trim((string) ($g['title'] ?? ''));
                    $gs = trim((string) ($g['subtitle'] ?? ''));
                    $gImg = trim((string) ($g['image_url'] ?? ''));
                @endphp
                @if ($gt !== '' || $gs !== '')
                    <div class="cmsw-team__group">
                        @if ($gImg !== '')
                            <div class="cmsw-team__group-image-wrap">
                                <img class="cmsw-team__group-image" src="{{ e($gImg) }}" alt="{{ e($gt) }}" loading="lazy">
                            </div>
                        @endif
                        <div class="cmsw-team__group-text">
                            @if ($gt !== '')
                                <h3 class="cmsw-team__group-title">{{ $gt }}</h3>
                            @endif
                            @if ($gs !== '')
                                <p class="cmsw-team__group-sub">{{ $gs }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @if ($quote !== '')
        <blockquote class="cmsw-team__quote">&ldquo;{{ $quote }}&rdquo;</blockquote>
    @endif

    @if (count($ctas) > 0)
        <div class="cmsw-team__actions">
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
                    $nt = ! empty($cta['new_tab']);
                @endphp
                <a class="btn-cmsw-primary" href="{{ e($url) }}" @if ($nt) target="_blank" rel="noopener noreferrer" @endif>{{ e($label) }} <span class="btn-arrow" aria-hidden="true">→</span></a>
            @endforeach
        </div>
    @endif

    @if ($hasImage)
        </div>
    @endif
</section>
