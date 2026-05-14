@php($s = optional($widget)->settings ?? [])
@php($defs = \App\Models\Widget::defaultJoinMovementSettings())

<fieldset class="js-home-join-movement-only join-movement-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Join the Movement (home CTA banner)') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the slug is “home-join-movement”. Heading, subtext, and two pill buttons on a gradient panel.') }}</p>

    <label class="field">
        <span>{{ __('Heading') }}</span>
        <input type="text" name="join_title" value="{{ old('join_title', $s['join_title'] ?? $defs['join_title'] ?? '') }}" maxlength="300">
        @error('join_title')<span class="error">{{ $message }}</span>@enderror
    </label>
    <label class="field">
        <span>{{ __('Subtext') }}</span>
        <textarea name="join_subtitle" rows="3" maxlength="1200">{{ old('join_subtitle', $s['join_subtitle'] ?? $defs['join_subtitle'] ?? '') }}</textarea>
        @error('join_subtitle')<span class="error">{{ $message }}</span>@enderror
    </label>

    <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);margin-top:0.75rem;background:rgba(0,0,0,0.02)">
        <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Left button') }}</h3>
        <label class="field">
            <span>{{ __('Label') }}</span>
            <input type="text" name="join_primary_label" value="{{ old('join_primary_label', $s['join_primary_label'] ?? $defs['join_primary_label'] ?? '') }}" maxlength="120">
            @error('join_primary_label')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Link URL') }}</span>
            <input type="text" name="join_primary_url" value="{{ old('join_primary_url', $s['join_primary_url'] ?? $defs['join_primary_url'] ?? '') }}" maxlength="2048" placeholder="/page/about">
            @error('join_primary_url')<span class="error">{{ $message }}</span>@enderror
        </label>
        <input type="hidden" name="join_primary_new_tab" value="0">
        <label class="check">
            <input type="checkbox" name="join_primary_new_tab" value="1" {{ old('join_primary_new_tab', ($s['join_primary_new_tab'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <span>{{ __('Open in new tab') }}</span>
        </label>
    </div>

    <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);margin-top:0.75rem;background:rgba(0,0,0,0.02)">
        <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Right button') }}</h3>
        <label class="field">
            <span>{{ __('Label') }}</span>
            <input type="text" name="join_secondary_label" value="{{ old('join_secondary_label', $s['join_secondary_label'] ?? $defs['join_secondary_label'] ?? '') }}" maxlength="120">
            @error('join_secondary_label')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Link URL') }}</span>
            <input type="text" name="join_secondary_url" value="{{ old('join_secondary_url', $s['join_secondary_url'] ?? $defs['join_secondary_url'] ?? '') }}" maxlength="2048" placeholder="/donate">
            @error('join_secondary_url')<span class="error">{{ $message }}</span>@enderror
        </label>
        <input type="hidden" name="join_secondary_new_tab" value="0">
        <label class="check">
            <input type="checkbox" name="join_secondary_new_tab" value="1" {{ old('join_secondary_new_tab', ($s['join_secondary_new_tab'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <span>{{ __('Open in new tab') }}</span>
        </label>
    </div>
</fieldset>

@push('scripts')
<script>
(function () {
    var slug = document.querySelector('input[name="slug"]');
    var blocks = document.querySelectorAll('.js-home-join-movement-only');
    if (!slug || !blocks.length) return;
    function sync() {
        var v = (slug.value || '').trim().toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
        var show = (v === 'home-join-movement') ? 'block' : 'none';
        blocks.forEach(function (el) { el.style.display = show; });
    }
    slug.addEventListener('input', sync);
    slug.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
