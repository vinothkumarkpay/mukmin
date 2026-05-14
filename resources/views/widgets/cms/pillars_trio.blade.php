@php
    $s = $widget->settings ?? [];
    $intro = trim((string) data_get($s, 'intro', ''));
    $pillars = data_get($s, 'pillars', []);
    if (! is_array($pillars)) {
        $pillars = [];
    }
    $accentClass = [
        'green' => 'cmsw-pillar--green',
        'blue' => 'cmsw-pillar--blue',
        'orange' => 'cmsw-pillar--orange',
    ];
@endphp
<section class="surface cms-page-widget cmsw-pillars">
    @if (trim((string) $widget->title) !== '')
        <h2 class="page-title cmsw-pillars__page-title">{{ $widget->title }}</h2>
    @endif
    @if ($intro !== '')
        <p class="cmsw-pillars__intro">{{ $intro }}</p>
    @endif
    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-pillars__lead">{!! $widget->content !!}</div>
    @endif
    <div class="cmsw-pillars__grid">
        @foreach ($pillars as $p)
            @php
                if (! is_array($p)) {
                    continue;
                }
                $title = trim((string) ($p['title'] ?? ''));
                $tag = trim((string) ($p['tagline'] ?? ''));
                $body = trim((string) ($p['body'] ?? ''));
                $more = trim((string) ($p['more'] ?? ''));
                $link = trim((string) ($p['link_url'] ?? ''));
                $linkLabel = trim((string) ($p['link_label'] ?? __('More info')));
                $ac = strtolower((string) ($p['accent'] ?? 'green'));
                $tone = $accentClass[$ac] ?? 'cmsw-pillar--green';
            @endphp
            @if ($title !== '' || $body !== '')
                <article class="cmsw-pillar {{ $tone }}">
                    @if ($tag !== '')
                        <p class="cmsw-pillar__tag">{{ $tag }}</p>
                    @endif
                    @if ($title !== '')
                        <h3 class="cmsw-pillar__title">{{ $title }}</h3>
                    @endif
                    @if ($body !== '')
                        <p class="cmsw-pillar__body">{{ $body }}</p>
                    @endif
                    @if ($more !== '')
                        <details class="cmsw-pillar__details">
                            <summary>{{ __('Read more') }}</summary>
                            <div class="cmsw-pillar__more cms-body">{!! $more !!}</div>
                        </details>
                    @endif
                    @if ($link !== '')
                        <p class="cmsw-pillar__linkwrap">
                            <a class="cmsw-pillar__link" href="{{ e($link) }}" @if (! empty($p['new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ e($linkLabel) }}</a>
                        </p>
                    @endif
                </article>
            @endif
        @endforeach
    </div>
</section>
