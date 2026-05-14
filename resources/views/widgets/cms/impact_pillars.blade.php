@php
    $s = $widget->settings ?? [];
    $items = data_get($s, 'pillars', []);
    if (! is_array($items)) {
        $items = [];
    }
    $toneClass = [
        'green' => 'cmsw-ipillar--green',
        'blue' => 'cmsw-ipillar--blue',
        'orange' => 'cmsw-ipillar--orange',
        'purple' => 'cmsw-ipillar--purple',
        'yellow' => 'cmsw-ipillar--yellow',
    ];
    $anchor = trim((string) data_get($s, 'anchor_id', ''));
@endphp
<section @if ($anchor !== '') id="{{ e($anchor) }}" @endif class="cms-page-widget cmsw-impact-pillars">
    @if (trim((string) $widget->title) !== '')
        <h2 class="cmsw-impact-pillars__title">{{ $widget->title }}</h2>
    @endif
    <div class="cmsw-ipillar-grid">
        @foreach ($items as $idx => $it)
            @php
                if (! is_array($it)) {
                    continue;
                }
                $label = trim((string) ($it['label'] ?? ''));
                $title = trim((string) ($it['title'] ?? ''));
                $body = trim((string) ($it['body'] ?? ''));
                $imageUrl = trim((string) ($it['image_url'] ?? ''));
                $bullets = $it['bullets'] ?? [];
                if (! is_array($bullets)) {
                    $bullets = [];
                }
                $ac = strtolower((string) ($it['accent'] ?? 'green'));
                $tone = $toneClass[$ac] ?? 'cmsw-ipillar--green';
                $cardId = 'ipillar-' . $idx;
            @endphp
            @if ($title !== '' || $body !== '')
                <article class="cmsw-ipillar {{ $tone }}">
                    @if ($imageUrl !== '')
                        <div class="cmsw-ipillar__image-wrap">
                            <img class="cmsw-ipillar__image" src="{{ e($imageUrl) }}" alt="{{ e($title) }}" loading="lazy">
                            <div class="cmsw-ipillar__image-overlay"></div>
                            @if ($label !== '')
                                <span class="cmsw-ipillar__image-label">{{ $label }}</span>
                            @endif
                        </div>
                    @elseif ($label !== '')
                        <p class="cmsw-ipillar__label">{{ $label }}</p>
                    @endif
                    <div class="cmsw-ipillar__content">
                        @if ($title !== '')
                            <h3 class="cmsw-ipillar__title">{{ $title }}</h3>
                        @endif
                        @if ($body !== '')
                            <p class="cmsw-ipillar__body">{{ $body }}</p>
                        @endif
                        @if (count($bullets) > 0)
                            <details class="cmsw-ipillar__details">
                                <summary class="cmsw-ipillar__toggle">{{ __('View key initiatives') }} <span class="cmsw-ipillar__toggle-arrow" aria-hidden="true">▾</span></summary>
                                <ul class="cmsw-ipillar__bullets">
                                    @foreach ($bullets as $b)
                                        @if (trim((string) $b) !== '')
                                            <li>{{ $b }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </details>
                        @endif
                    </div>
                </article>
            @endif
        @endforeach
    </div>
</section>
