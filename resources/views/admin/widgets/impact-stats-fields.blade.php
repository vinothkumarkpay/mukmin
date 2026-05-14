@php($s = optional($widget)->settings ?? [])
@php($defs = \App\Models\Widget::defaultImpactStatsSettings())
@php($defCards = $defs['impact_cards'] ?? [])

<fieldset class="js-home-impact-stats-only impact-stats-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Measured outcomes (home stats)') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the slug is “home-impact-stats”. Three cards appear in a row on desktop and stack on small screens.') }}</p>

    <label class="field">
        <span>{{ __('Eyebrow line (uppercase style)') }}</span>
        <input type="text" name="impact_eyebrow" value="{{ old('impact_eyebrow', $s['impact_eyebrow'] ?? $defs['impact_eyebrow'] ?? '') }}" maxlength="500">
        @error('impact_eyebrow')<span class="error">{{ $message }}</span>@enderror
    </label>
    <label class="field">
        <span>{{ __('Main heading') }}</span>
        <input type="text" name="impact_title" value="{{ old('impact_title', $s['impact_title'] ?? $defs['impact_title'] ?? '') }}" maxlength="300">
        @error('impact_title')<span class="error">{{ $message }}</span>@enderror
    </label>
    <label class="field">
        <span>{{ __('Subtitle') }}</span>
        <textarea name="impact_subtitle" rows="2" maxlength="1000">{{ old('impact_subtitle', $s['impact_subtitle'] ?? $defs['impact_subtitle'] ?? '') }}</textarea>
        @error('impact_subtitle')<span class="error">{{ $message }}</span>@enderror
    </label>

    @for ($i = 0; $i < \App\Models\Widget::IMPACT_STAT_CARD_SLOTS; $i++)
        @php($card = isset($s['impact_cards'][$i]) && is_array($s['impact_cards'][$i]) ? $s['impact_cards'][$i] : [])
        @php($dc = $defCards[$i] ?? [])
        <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);margin-top:0.75rem;background:rgba(0,0,0,0.02)">
            <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Stat card') }} {{ $i + 1 }}</h3>
            <label class="field">
                <span>{{ __('Big number / stat') }}</span>
                <input type="text" name="impact_cards[{{ $i }}][stat]" value="{{ old("impact_cards.$i.stat", $card['stat'] ?? $dc['stat'] ?? '') }}" maxlength="64" placeholder="2,000+">
                @error("impact_cards.$i.stat")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Description') }}</span>
                <textarea name="impact_cards[{{ $i }}][description]" rows="2" maxlength="500">{{ old("impact_cards.$i.description", $card['description'] ?? $dc['description'] ?? '') }}</textarea>
                @error("impact_cards.$i.description")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Card colour') }}</span>
                @php($th = old("impact_cards.$i.card_theme", $card['card_theme'] ?? $dc['card_theme'] ?? 'blue'))
                <select name="impact_cards[{{ $i }}][card_theme]">
                    <option value="blue" {{ $th === 'blue' ? 'selected' : '' }}>{{ __('Soft blue') }}</option>
                    <option value="mint" {{ $th === 'mint' ? 'selected' : '' }}>{{ __('Soft mint') }}</option>
                    <option value="cyan" {{ $th === 'cyan' ? 'selected' : '' }}>{{ __('Soft cyan') }}</option>
                </select>
                @error("impact_cards.$i.card_theme")<span class="error">{{ $message }}</span>@enderror
            </label>
        </div>
    @endfor
</fieldset>

@push('scripts')
<script>
(function () {
    var slug = document.querySelector('input[name="slug"]');
    var blocks = document.querySelectorAll('.js-home-impact-stats-only');
    if (!slug || !blocks.length) return;
    function sync() {
        var v = (slug.value || '').trim().toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
        var show = (v === 'home-impact-stats') ? 'block' : 'none';
        blocks.forEach(function (el) { el.style.display = show; });
    }
    slug.addEventListener('input', sync);
    slug.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
