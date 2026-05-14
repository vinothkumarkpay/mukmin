@php($s = optional($widget)->settings ?? [])
@php($hasHeroBg = $widget && !empty($s['hero_bg_image_path']))
@php($hasSidePanel = $widget && !empty($s['hero_side_panel_image_path']))

<fieldset class="js-home-hero-only hero-appearance-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Home hero appearance') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the slug is “home-hero”. Full-bleed background is optional; the right-hand hero panel uses its own image below. Tune gradient colours and wash strength to match your photography.') }}</p>

    <label class="field">
        <span>{{ __('Background image file') }}</span>
        <input type="file" name="hero_background_image" accept="image/jpeg,image/png,image/webp,image/gif">
        @error('hero_background_image')<span class="error">{{ $message }}</span>@enderror
    </label>

    @if ($hasHeroBg)
        <input type="hidden" name="remove_hero_background" value="0">
        <label class="check">
            <input type="checkbox" name="remove_hero_background" value="1" {{ old('remove_hero_background') === '1' ? 'checked' : '' }}>
            <span>{{ __('Remove uploaded background image') }}</span>
        </label>
        <p class="muted" style="margin:0.35rem 0 0;font-size:0.85rem">{{ __('Current file is stored on the server; remove it before replacing with a URL-only background if you prefer.') }}</p>
    @endif

    <label class="field">
        <span>{{ __('Background image URL (optional)') }}</span>
        <input type="url" name="hero_bg_external_url" value="{{ old('hero_bg_external_url', $s['hero_bg_external_url'] ?? '') }}" maxlength="2048" placeholder="https://…">
        @error('hero_bg_external_url')<span class="error">{{ $message }}</span>@enderror
        <span class="muted" style="font-weight:400">{{ __('Used when no uploaded file is set (or as fallback after removal).') }}</span>
    </label>

    <hr style="border:0;border-top:1px solid var(--color-border);margin:1.25rem 0">

    <p class="muted" style="margin:0 0 0.75rem;font-size:0.9rem">{{ __('Right-hand hero panel — a framed image on large screens (above the “Join” area). Upload a portrait or square crop, or paste an image URL. Leave the URL empty to use rotating built‑in photos (green, outdoor settings with people).') }}</p>

    <label class="field">
        <span>{{ __('Hero side panel image file') }}</span>
        <input type="file" name="hero_side_panel_image" accept="image/jpeg,image/png,image/webp,image/gif">
        @error('hero_side_panel_image')<span class="error">{{ $message }}</span>@enderror
    </label>

    @if ($hasSidePanel)
        <input type="hidden" name="remove_hero_side_panel" value="0">
        <label class="check">
            <input type="checkbox" name="remove_hero_side_panel" value="1" {{ old('remove_hero_side_panel') === '1' ? 'checked' : '' }}>
            <span>{{ __('Remove uploaded side panel image') }}</span>
        </label>
        <p class="muted" style="margin:0.35rem 0 0;font-size:0.85rem">{{ __('After removal you can rely on the URL field or the built-in default stock image.') }}</p>
    @endif

    <label class="field">
        <span>{{ __('Hero side panel image URL (optional)') }}</span>
        <input type="url" name="hero_side_panel_external_url" value="{{ old('hero_side_panel_external_url', trim($s['hero_side_panel_external_url'] ?? '')) }}" maxlength="2048" placeholder="https://…">
        @error('hero_side_panel_external_url')<span class="error">{{ $message }}</span>@enderror
        <span class="muted" style="font-weight:400">{{ __('Used when no side panel file is uploaded. Leave blank for rotating community / greenery stock images.') }}</span>
    </label>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(10rem,1fr));gap:0.75rem;margin-top:0.75rem">
        <label class="field">
            <span>{{ __('Gradient start') }}</span>
            <input type="text" name="hero_gradient_1" value="{{ old('hero_gradient_1', $s['hero_gradient_1'] ?? '#fff7ed') }}" maxlength="7" pattern="#[0-9A-Fa-f]{6}" placeholder="#fff7ed">
            @error('hero_gradient_1')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Gradient middle') }}</span>
            <input type="text" name="hero_gradient_2" value="{{ old('hero_gradient_2', $s['hero_gradient_2'] ?? '#ffd9b3') }}" maxlength="7" pattern="#[0-9A-Fa-f]{6}" placeholder="#ffd9b3">
            @error('hero_gradient_2')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Gradient end') }}</span>
            <input type="text" name="hero_gradient_3" value="{{ old('hero_gradient_3', $s['hero_gradient_3'] ?? '#5bbfaa') }}" maxlength="7" pattern="#[0-9A-Fa-f]{6}" placeholder="#5bbfaa">
            @error('hero_gradient_3')<span class="error">{{ $message }}</span>@enderror
        </label>
    </div>

    <label class="field" style="margin-top:0.75rem">
        <span>{{ __('Colour wash strength') }} (0–0.92)</span>
        <input type="number" name="hero_overlay" value="{{ old('hero_overlay', $s['hero_overlay'] ?? 0.42) }}" min="0" max="0.92" step="0.02">
        @error('hero_overlay')<span class="error">{{ $message }}</span>@enderror
        <span class="muted" style="font-weight:400">{{ __('Higher values make the gradient more dominant over the photo.') }}</span>
    </label>
</fieldset>

@push('scripts')
<script>
(function () {
    var slug = document.querySelector('input[name="slug"]');
    var blocks = document.querySelectorAll('.js-home-hero-only');
    if (!slug || !blocks.length) return;
    function sync() {
        var v = (slug.value || '').trim().toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
        var show = (v === 'home-hero') ? 'block' : 'none';
        blocks.forEach(function (el) { el.style.display = show; });
    }
    slug.addEventListener('input', sync);
    slug.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
