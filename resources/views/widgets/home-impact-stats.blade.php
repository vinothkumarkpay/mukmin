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
                            {{-- Scholarship: graduation cap + book + RM badge --}}
                            <svg class="home-impact-stats__svg" viewBox="0 0 88 80" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <circle cx="44" cy="42" r="36" fill="currentColor" fill-opacity="0.08"/>
                                <circle cx="44" cy="42" r="36" stroke="currentColor" stroke-width="1" stroke-dasharray="3 5" stroke-opacity="0.22"/>
                                <path d="M16 54 Q16 52 18 52 L42 52 L44 55 L46 52 L70 52 Q72 52 72 54 L72 70 Q72 72 70 72 L46 72 L44 69 L42 72 L18 72 Q16 72 16 70 Z" fill="currentColor" fill-opacity="0.18" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M44 55 L44 69" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M22 58 L40 58 M22 62 L40 62 M22 66 L36 66" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-opacity="0.55"/>
                                <path d="M48 58 L66 58 M48 62 L66 62 M52 66 L66 66" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-opacity="0.55"/>
                                <path d="M44 14 L74 24 L44 34 L14 24 Z" fill="currentColor"/>
                                <path d="M58 30 L58 42 Q58 46 44 46 Q30 46 30 42 L30 30" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                <path d="M74 24 L74 38" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <circle cx="74" cy="40" r="2.2" fill="currentColor"/>
                                <circle cx="78" cy="14" r="8" fill="currentColor"/>
                                <text x="78" y="17.5" text-anchor="middle" font-size="8" font-weight="800" fill="#fff" font-family="system-ui,sans-serif">RM</text>
                            </svg>
                        @elseif ($card['card_theme'] === 'cyan')
                            {{-- Families: house with heart and door --}}
                            <svg class="home-impact-stats__svg" viewBox="0 0 88 80" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                <circle cx="44" cy="42" r="36" fill="currentColor" fill-opacity="0.08"/>
                                <circle cx="44" cy="42" r="36" stroke="currentColor" stroke-width="1" stroke-dasharray="3 5" stroke-opacity="0.22"/>
                                <path d="M20 46 L20 70 Q20 72 22 72 L66 72 Q68 72 68 70 L68 46" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M12 48 L44 22 L76 48" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                <rect x="40" y="58" width="9" height="14" rx="1.5" fill="currentColor" opacity="0.45"/>
                                <circle cx="47" cy="65" r="0.9" fill="#fff"/>
                                <path d="M44 38 C42 34 34 34 34 41 C34 47 44 53 44 53 C44 53 54 47 54 41 C54 34 46 34 44 38 Z" fill="currentColor"/>
                                <path d="M44 38 C42 34 34 34 34 41 C34 47 44 53 44 53 C44 53 54 47 54 41 C54 34 46 34 44 38 Z" fill="#fff" fill-opacity="0.25"/>
                                <rect x="56" y="32" width="6" height="10" rx="1" fill="currentColor" opacity="0.55"/>
                                <text x="72" y="22" font-size="9" fill="currentColor" opacity="0.55">✦</text>
                                <text x="14" y="34" font-size="7" fill="currentColor" opacity="0.45">✦</text>
                            </svg>
                        @else
                            {{-- Delegates summit: 3 numbered programme tabs at top, funneling into a group of delegates below --}}
                            <svg class="home-impact-stats__svg" viewBox="0 0 88 80" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                {{-- Subtle community ring background --}}
                                <circle cx="44" cy="42" r="36" fill="currentColor" fill-opacity="0.08"/>
                                <circle cx="44" cy="42" r="36" stroke="currentColor" stroke-width="1" stroke-dasharray="3 5" stroke-opacity="0.22"/>

                                {{-- Programme tab 1 (left, outlined) --}}
                                <rect x="6" y="6" width="22" height="14" rx="7" fill="currentColor" fill-opacity="0.14"/>
                                <rect x="6" y="6" width="22" height="14" rx="7" stroke="currentColor" stroke-width="1.6" fill="none"/>
                                <text x="17" y="16.5" text-anchor="middle" font-size="8.5" font-weight="800" fill="currentColor" font-family="system-ui,sans-serif">1</text>

                                {{-- Programme tab 2 (centre, solid / active) --}}
                                <rect x="32" y="4" width="24" height="16" rx="8" fill="currentColor"/>
                                <text x="44" y="16" text-anchor="middle" font-size="10" font-weight="800" fill="#fff" font-family="system-ui,sans-serif">2</text>

                                {{-- Programme tab 3 (right, outlined) --}}
                                <rect x="60" y="6" width="22" height="14" rx="7" fill="currentColor" fill-opacity="0.14"/>
                                <rect x="60" y="6" width="22" height="14" rx="7" stroke="currentColor" stroke-width="1.6" fill="none"/>
                                <text x="71" y="16.5" text-anchor="middle" font-size="8.5" font-weight="800" fill="currentColor" font-family="system-ui,sans-serif">3</text>

                                {{-- Dashed funnel lines from each tab down to the delegate group --}}
                                <path d="M17 22 L24 32" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-opacity="0.4" stroke-dasharray="1.5 2"/>
                                <path d="M44 22 L44 32" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-opacity="0.55" stroke-dasharray="1.5 2"/>
                                <path d="M71 22 L64 32" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-opacity="0.4" stroke-dasharray="1.5 2"/>

                                {{-- Stage / arena line --}}
                                <path d="M8 73 Q44 72 80 73" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-opacity="0.2" fill="none"/>

                                {{-- Central prominent delegate (host / speaker) --}}
                                <circle cx="44" cy="42" r="6" fill="currentColor"/>
                                <path d="M34 58 Q44 49 54 58 L54 70 Q54 73 51 73 L37 73 Q34 73 34 70 Z" fill="currentColor"/>

                                {{-- Mid-left delegate --}}
                                <circle cx="30" cy="49" r="4.5" fill="currentColor" opacity="0.78"/>
                                <path d="M22 62 Q30 54 38 62 L38 72 Q38 73 37 73 L23 73 Q22 73 22 72 Z" fill="currentColor" opacity="0.78"/>

                                {{-- Mid-right delegate --}}
                                <circle cx="58" cy="49" r="4.5" fill="currentColor" opacity="0.78"/>
                                <path d="M50 62 Q58 54 66 62 L66 72 Q66 73 65 73 L51 73 Q50 73 50 72 Z" fill="currentColor" opacity="0.78"/>

                                {{-- Far-left delegate --}}
                                <circle cx="14" cy="52" r="3.8" fill="currentColor" opacity="0.55"/>
                                <path d="M7 64 Q14 57 21 64 L21 72 L7 72 Z" fill="currentColor" opacity="0.55"/>

                                {{-- Far-right delegate --}}
                                <circle cx="74" cy="52" r="3.8" fill="currentColor" opacity="0.55"/>
                                <path d="M67 64 Q74 57 81 64 L81 72 L67 72 Z" fill="currentColor" opacity="0.55"/>
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
