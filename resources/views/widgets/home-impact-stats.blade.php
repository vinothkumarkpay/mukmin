@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultImpactStatsSettings();
    $eyebrow = trim((string) ($s['impact_eyebrow'] ?? $defs['impact_eyebrow'] ?? ''));
    $title = trim((string) ($s['impact_title'] ?? $defs['impact_title'] ?? ''));
    $subtitle = trim((string) ($s['impact_subtitle'] ?? $defs['impact_subtitle'] ?? ''));
    $cardsIn = $s['impact_cards'] ?? $defs['impact_cards'] ?? [];
    $cards = [];
    for ($ci = 0; $ci < \App\Models\Widget::IMPACT_STAT_CARD_SLOTS; $ci++) {
        $row = isset($cardsIn[$ci]) && is_array($cardsIn[$ci]) ? $cardsIn[$ci] : [];
        $def = ($defs['impact_cards'][$ci] ?? []);
        $theme = strtolower((string) ($row['card_theme'] ?? $def['card_theme'] ?? 'blue'));
        if (! in_array($theme, ['blue', 'mint', 'cyan'], true)) {
            $theme = 'blue';
        }
        $cards[] = [
            'stat' => trim((string) ($row['stat'] ?? $def['stat'] ?? '')),
            'description' => trim((string) ($row['description'] ?? $def['description'] ?? '')),
            'card_theme' => $theme,
        ];
    }
@endphp
<section class="home-impact-stats" aria-labelledby="home-impact-stats-heading">
    <div class="home-impact-stats__inner">
        <header class="home-impact-stats__header">
            @if ($eyebrow !== '')
                <p class="home-impact-stats__eyebrow">{{ $eyebrow }}</p>
            @endif
            @if ($title !== '')
                <h2 id="home-impact-stats-heading" class="home-impact-stats__title">{{ $title }}</h2>
            @endif
            @if ($subtitle !== '')
                <p class="home-impact-stats__subtitle">{{ $subtitle }}</p>
            @endif
        </header>
        <ul class="home-impact-stats__grid">
            @foreach ($cards as $card)
                <li class="home-impact-stats__card home-impact-stats__card--{{ $card['card_theme'] }}">
                    <div class="home-impact-stats__icon" aria-hidden="true">
                        @if ($card['card_theme'] === 'mint')
                            <svg class="home-impact-stats__svg" viewBox="0 0 64 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <rect x="8" y="14" width="48" height="32" rx="4" fill="currentColor" opacity="0.2"/>
                                <rect x="12" y="18" width="40" height="24" rx="2" stroke="currentColor" stroke-width="2.2"/>
                                <text x="32" y="34" text-anchor="middle" font-size="14" font-weight="800" fill="currentColor" font-family="system-ui,sans-serif">RM</text>
                            </svg>
                        @elseif ($card['card_theme'] === 'cyan')
                            <svg class="home-impact-stats__svg" viewBox="0 0 64 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <rect x="26" y="6" width="12" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.15"/>
                                <path d="M32 16v8M14 30h36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <rect x="8" y="30" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.12"/>
                                <rect x="24" y="30" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.12"/>
                                <rect x="40" y="30" width="16" height="12" rx="2" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.12"/>
                            </svg>
                        @else
                            <svg class="home-impact-stats__svg" viewBox="0 0 64 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <circle cx="22" cy="22" r="7" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.12"/>
                                <circle cx="38" cy="20" r="6" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.12"/>
                                <path d="M28 28 L40 34" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <rect x="38" y="32" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.08"/>
                                <rect x="42" y="36" width="10" height="3" rx="1" fill="currentColor" opacity="0.35"/>
                                <rect x="42" y="41" width="10" height="3" rx="1" fill="currentColor" opacity="0.35"/>
                                <rect x="42" y="46" width="10" height="3" rx="1" fill="currentColor" opacity="0.35"/>
                                <circle cx="52" cy="30" r="7" fill="#e85d4c"/>
                                <text x="52" y="33.5" text-anchor="middle" font-size="8" font-weight="800" fill="#fff" font-family="system-ui,sans-serif">3</text>
                            </svg>
                        @endif
                    </div>
                    @if ($card['stat'] !== '')
                        <p class="home-impact-stats__stat">{{ $card['stat'] }}</p>
                    @endif
                    @if ($card['description'] !== '')
                        <p class="home-impact-stats__desc">{{ $card['description'] }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
