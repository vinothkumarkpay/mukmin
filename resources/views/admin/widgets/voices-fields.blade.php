@php($s = optional($widget)->settings ?? [])
@php($defs = \App\Models\Widget::defaultVoicesSettings())
@php($defItems = $defs['voices_items'] ?? [])

<fieldset class="js-home-voices-only voices-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Voices of Change (testimonials)') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the slug is “home-voices”. Cards scroll horizontally in a loop; add as many voices as you need (empty rows are ignored).') }}</p>

    <label class="field">
        <span>{{ __('Eyebrow line') }}</span>
        <input type="text" name="voices_eyebrow" value="{{ old('voices_eyebrow', $s['voices_eyebrow'] ?? $defs['voices_eyebrow'] ?? '') }}" maxlength="500">
        @error('voices_eyebrow')<span class="error">{{ $message }}</span>@enderror
    </label>
    <label class="field">
        <span>{{ __('Main heading') }}</span>
        <input type="text" name="voices_title" value="{{ old('voices_title', $s['voices_title'] ?? $defs['voices_title'] ?? '') }}" maxlength="300">
        @error('voices_title')<span class="error">{{ $message }}</span>@enderror
    </label>

    <div style="display:grid;gap:0.75rem;margin:1rem 0">
        <label class="field">
            <span>{{ __('Scroll loop duration (seconds)') }}</span>
            <input type="number" name="voices_marquee_seconds" value="{{ old('voices_marquee_seconds', $s['voices_marquee_seconds'] ?? 0) }}" min="0" max="180" step="1">
            @error('voices_marquee_seconds')<span class="error">{{ $message }}</span>@enderror
            <span class="muted" style="font-weight:400">{{ __('Use 0 for automatic timing based on how many voices you have.') }}</span>
        </label>
        <input type="hidden" name="voices_marquee_autoplay" value="0">
        <label class="check">
            <input type="checkbox" name="voices_marquee_autoplay" value="1" {{ old('voices_marquee_autoplay', ($s['voices_marquee_autoplay'] ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <span>{{ __('Continuous horizontal scroll') }}</span>
        </label>
    </div>

    @for ($i = 0; $i < \App\Models\Widget::VOICES_MAX_SLOTS; $i++)
        @php($row = isset($s['voices_items'][$i]) && is_array($s['voices_items'][$i]) ? $s['voices_items'][$i] : [])
        @php($di = $defItems[$i] ?? [])
        <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);margin-top:0.5rem;background:rgba(0,0,0,0.02)">
            <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Voice') }} {{ $i + 1 }}</h3>
            <label class="field">
                <span>{{ __('Name') }}</span>
                <input type="text" name="voices_items[{{ $i }}][name]" value="{{ old("voices_items.$i.name", $row['name'] ?? $di['name'] ?? '') }}" maxlength="120">
                @error("voices_items.$i.name")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Role / context') }}</span>
                <input type="text" name="voices_items[{{ $i }}][role]" value="{{ old("voices_items.$i.role", $row['role'] ?? $di['role'] ?? '') }}" maxlength="255">
                @error("voices_items.$i.role")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Quote') }}</span>
                <textarea name="voices_items[{{ $i }}][quote]" rows="3" maxlength="1200">{{ old("voices_items.$i.quote", $row['quote'] ?? $di['quote'] ?? '') }}</textarea>
                @error("voices_items.$i.quote")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Avatar initial (optional)') }}</span>
                <input type="text" name="voices_items[{{ $i }}][initial]" value="{{ old("voices_items.$i.initial", $row['initial'] ?? $di['initial'] ?? '') }}" maxlength="4" placeholder="N">
                @error("voices_items.$i.initial")<span class="error">{{ $message }}</span>@enderror
            </label>
        </div>
    @endfor
</fieldset>

@push('scripts')
<script>
(function () {
    var slug = document.querySelector('input[name="slug"]');
    var blocks = document.querySelectorAll('.js-home-voices-only');
    if (!slug || !blocks.length) return;
    function sync() {
        var v = (slug.value || '').trim().toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
        var show = (v === 'home-voices') ? 'block' : 'none';
        blocks.forEach(function (el) { el.style.display = show; });
    }
    slug.addEventListener('input', sync);
    slug.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
