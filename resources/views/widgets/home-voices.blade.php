@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultVoicesSettings();
    $eyebrow = trim((string) ($s['voices_eyebrow'] ?? $defs['voices_eyebrow'] ?? ''));
    $title = trim((string) ($s['voices_title'] ?? $defs['voices_title'] ?? ''));
    $auto = array_key_exists('voices_marquee_autoplay', $s) ? (bool) $s['voices_marquee_autoplay'] : (bool) ($defs['voices_marquee_autoplay'] ?? true);
    $secStored = (int) ($s['voices_marquee_seconds'] ?? 0);
    $rawItems = $s['voices_items'] ?? $defs['voices_items'] ?? [];
    $voices = [];
    foreach ($rawItems as $it) {
        if (! is_array($it)) {
            continue;
        }
        $q = trim((string) ($it['quote'] ?? ''));
        $n = trim((string) ($it['name'] ?? ''));
        if ($q === '' && $n === '') {
            continue;
        }
        $voices[] = [
            'name' => $n,
            'role' => trim((string) ($it['role'] ?? '')),
            'quote' => $q,
            'initial' => trim((string) ($it['initial'] ?? '')) !== ''
                ? mb_strtoupper(mb_substr(trim((string) ($it['initial'])), 0, 1, 'UTF-8'), 'UTF-8')
                : ($n !== '' ? mb_strtoupper(mb_substr($n, 0, 1, 'UTF-8'), 'UTF-8') : '?'),
        ];
    }
    $nVoices = count($voices);
    $loopSec = $secStored > 0 ? max(12, min(180, $secStored)) : max(24, min(120, $nVoices * 13));
    $dup = $nVoices > 0 ? array_merge($voices, $voices) : [];
@endphp
@if ($nVoices > 0)
    <section
        class="home-voices @if (! $auto) home-voices--paused @endif"
        aria-labelledby="home-voices-heading"
        style="--voices-loop-sec: {{ $loopSec }}s;"
    >
        <div class="home-voices__inner">
            @if ($eyebrow !== '' || $title !== '')
                <header class="home-voices__header">
                    @if ($eyebrow !== '')
                        <p class="home-voices__eyebrow">{{ e($eyebrow) }}</p>
                    @endif
                    @if ($title !== '')
                        <h2 id="home-voices-heading" class="home-voices__title">{{ e($title) }}</h2>
                    @endif
                </header>
            @endif
            <div class="home-voices__viewport">
                <div class="home-voices__track">
                    @foreach ($dup as $v)
                        <article class="home-voices__card">
                            <div class="home-voices__card-head">
                                <span class="home-voices__avatar" aria-hidden="true">{{ e($v['initial']) }}</span>
                                <div class="home-voices__meta">
                                    @if ($v['name'] !== '')
                                        <strong class="home-voices__name">{{ e($v['name']) }}</strong>
                                    @endif
                                    @if ($v['role'] !== '')
                                        <span class="home-voices__role">{{ e($v['role']) }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($v['quote'] !== '')
                                <blockquote class="home-voices__quote">
                                    <span class="home-voices__quote-mark" aria-hidden="true">“</span>{{ e($v['quote']) }}<span class="home-voices__quote-mark" aria-hidden="true">”</span>
                                </blockquote>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
