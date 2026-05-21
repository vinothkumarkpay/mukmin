@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultVoicesSettings();
    $eyebrow = ''; // Forcibly remove "VOICES OF CHANGE" eyebrow line
    $title = trim((string) ($s['voices_title'] ?? $defs['voices_title'] ?? ''));
    if (strtolower($title) === 'what participants are saying' || $title === '') {
        $title = 'Voices of Mukmin';
    }
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

    if (!function_exists('getYoutubeId')) {
        function getYoutubeId($url) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches)) {
                return $matches[1];
            }
            return null;
        }
    }

    $unsplashThumbnails = [
        'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=480&h=270&q=80',
        'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=480&h=270&q=80',
        'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=480&h=270&q=80',
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=480&h=270&q=80',
        'https://images.unsplash.com/photo-1531545514256-b1400bc00f31?auto=format&fit=crop&w=480&h=270&q=80',
    ];
@endphp
@if ($nVoices > 0)
    <section
        class="home-voices @if (! $auto) home-voices--paused @endif"
        aria-labelledby="home-voices-heading"
        style="--voices-loop-sec: {{ $loopSec }}s;"
    >
        <div class="home-voices__inner">
            @if ($title !== '')
                <header class="home-voices__header">
                    <h2 id="home-voices-heading" class="home-voices__title">{{ e($title) }}</h2>
                </header>
            @endif
            <div class="home-voices__viewport">
                <div class="home-voices__track">
                    @foreach ($dup as $index => $v)
                        @php
                            $ytId = getYoutubeId($v['quote']);
                            $thumb = $ytId 
                                ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" 
                                : ($unsplashThumbnails[$index % count($unsplashThumbnails)] ?? '');
                        @endphp
                        <a href="{{ $v['quote'] ?: '#' }}" target="_blank" rel="noopener noreferrer" class="home-voices__card">
                            <div class="home-voices__video-container">
                                @if ($thumb !== '')
                                    <img class="home-voices__thumbnail" src="{{ $thumb }}" alt="{{ $v['name'] }}" loading="lazy">
                                @endif
                                <div class="home-voices__play-overlay">
                                    <div class="home-voices__play-btn">
                                        <svg class="home-voices__play-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="home-voices__card-footer">
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
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
