@php
    $s = $widget->settings ?? [];
    $tagline = trim((string) data_get($s, 'tagline', ''));
    $ctas = data_get($s, 'ctas', []);
    if (! is_array($ctas)) {
        $ctas = [];
    }
@endphp
<section class="surface cms-page-widget cmsw-spotlight">
    @if (trim((string) $widget->title) !== '')
        <h2 class="cmsw-spotlight__title">{{ $widget->title }}</h2>
    @endif
    @if ($tagline !== '')
        <p class="cmsw-spotlight__tagline">{{ $tagline }}</p>
    @endif
    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-spotlight__body">{!! $widget->content !!}</div>
    @endif
    @if (count($ctas) > 0)
        <div class="cmsw-spotlight__actions">
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
</section>
