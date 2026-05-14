@php
    $s = $widget->settings ?? [];
    $defs = \App\Models\Widget::defaultJoinMovementSettings();
    $title = trim((string) ($s['join_title'] ?? $defs['join_title'] ?? ''));
    $subtitle = trim((string) ($s['join_subtitle'] ?? $defs['join_subtitle'] ?? ''));
    $pLabel = trim((string) ($s['join_primary_label'] ?? $defs['join_primary_label'] ?? ''));
    $pUrl = trim((string) ($s['join_primary_url'] ?? $defs['join_primary_url'] ?? ''));
    $pNewTab = (bool) ($s['join_primary_new_tab'] ?? $defs['join_primary_new_tab'] ?? false);
    $secLabel = trim((string) ($s['join_secondary_label'] ?? $defs['join_secondary_label'] ?? ''));
    $secUrl = trim((string) ($s['join_secondary_url'] ?? $defs['join_secondary_url'] ?? ''));
    $secNewTab = (bool) ($s['join_secondary_new_tab'] ?? $defs['join_secondary_new_tab'] ?? false);
    $showPrimary = $pLabel !== '' && $pUrl !== '';
    $showSecondary = $secLabel !== '' && $secUrl !== '';
    $hasContent = $title !== '' || $subtitle !== '' || $showPrimary || $showSecondary;
@endphp
@if ($hasContent)
    <div class="home-join-movement-wrap">
        <section class="home-join-movement" aria-labelledby="home-join-movement-heading">
            <div class="home-join-movement__inner">
                @if ($title !== '')
                    <h2 id="home-join-movement-heading" class="home-join-movement__title">{{ e($title) }}</h2>
                @endif
                @if ($subtitle !== '')
                    <p class="home-join-movement__subtitle">{{ e($subtitle) }}</p>
                @endif
                @if ($showPrimary || $showSecondary)
                    <div class="home-join-movement__actions">
                        <div class="home-join-movement__actions-start">
                            @if ($showPrimary)
                                <a
                                    class="home-join-movement__btn"
                                    href="{{ e($pUrl) }}"
                                    @if ($pNewTab) target="_blank" rel="noopener noreferrer" @endif
                                >{{ e($pLabel) }}</a>
                            @endif
                        </div>
                        <div class="home-join-movement__actions-end">
                            @if ($showSecondary)
                                <a
                                    class="home-join-movement__btn"
                                    href="{{ e($secUrl) }}"
                                    @if ($secNewTab) target="_blank" rel="noopener noreferrer" @endif
                                >{{ e($secLabel) }}</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endif
