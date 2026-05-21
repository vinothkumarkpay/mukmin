@php($s = optional($widget)->settings ?? [])
@php($defaults = \App\Models\Widget::defaultHeroSlides())

<fieldset class="js-home-hero-only hero-slides-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Home hero initiative banners') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Up to four linked images rotate below the hero copy. About three slides are visible at once with a peek of the next. Upload a file or paste an image URL per banner.') }}</p>

    <div style="display:grid;gap:0.75rem;margin-bottom:1rem">
        <label class="field">
            <span>{{ __('Auto-advance interval (seconds)') }}</span>
            <input type="number" name="hero_slide_interval" value="{{ old('hero_slide_interval', $s['hero_slide_interval'] ?? 6) }}" min="3" max="120" step="1">
            @error('hero_slide_interval')<span class="error">{{ $message }}</span>@enderror
        </label>
        <input type="hidden" name="hero_slides_autoplay" value="0">
        <label class="check">
            <input type="checkbox" name="hero_slides_autoplay" value="1" {{ old('hero_slides_autoplay', ($s['hero_slides_autoplay'] ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <span>{{ __('Auto-scroll banners') }}</span>
        </label>
    </div>

    @for ($i = 0; $i < \App\Models\Widget::HERO_SLIDE_SLOTS; $i++)
        @php($slide = isset($s['hero_slides'][$i]) && is_array($s['hero_slides'][$i]) ? $s['hero_slides'][$i] : [])
        @php($def = $defaults[$i] ?? [])
        @php($hasSlideFile = !empty($slide['image_path']))
        <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);background:rgba(0,0,0,0.02)">
            <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Banner') }} {{ $i + 1 }}</h3>
            <label class="field">
                <span>{{ __('Slide heading (shown on the card)') }}</span>
                <input type="text" name="hero_slides[{{ $i }}][title]" value="{{ old("hero_slides.$i.title", $slide['title'] ?? $def['title'] ?? '') }}" maxlength="255">
                @error("hero_slides.$i.title")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Sub-header / tagline (shown above the description)') }}</span>
                <input type="text" name="hero_slides[{{ $i }}][subtitle]" value="{{ old("hero_slides.$i.subtitle", $slide['subtitle'] ?? $def['subtitle'] ?? '') }}" maxlength="255" placeholder="{{ __('Short tagline (optional)') }}">
                @error("hero_slides.$i.subtitle")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Short description (shown on hover / touch)') }}</span>
                <textarea name="hero_slides[{{ $i }}][description]" rows="3" maxlength="2000" class="code" style="min-height:4.5rem">{{ old("hero_slides.$i.description", $slide['description'] ?? $def['description'] ?? '') }}</textarea>
                @error("hero_slides.$i.description")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Button label (e.g. “More details”)') }}</span>
                <input type="text" name="hero_slides[{{ $i }}][cta_label]" value="{{ old("hero_slides.$i.cta_label", $slide['cta_label'] ?? $def['cta_label'] ?? '') }}" maxlength="120" placeholder="{{ __('More details') }}">
                @error("hero_slides.$i.cta_label")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Link URL') }}</span>
                <input type="text" name="hero_slides[{{ $i }}][link_url]" value="{{ old("hero_slides.$i.link_url", $slide['link_url'] ?? $def['link_url'] ?? '') }}" maxlength="2048" placeholder="/page/about or https://…">
                @error("hero_slides.$i.link_url")<span class="error">{{ $message }}</span>@enderror
            </label>
            <input type="hidden" name="hero_slides[{{ $i }}][new_tab]" value="0">
            <label class="check">
                <input type="checkbox" name="hero_slides[{{ $i }}][new_tab]" value="1" {{ old("hero_slides.$i.new_tab", !empty($slide['new_tab']) ? '1' : '0') === '1' ? 'checked' : '' }}>
                <span>{{ __('Open link in new tab') }}</span>
            </label>
            <label class="field" style="margin-top:0.5rem">
                <span>{{ __('Image URL (optional if you upload a file)') }}</span>
                <input type="url" name="hero_slides[{{ $i }}][image_url]" value="{{ old("hero_slides.$i.image_url", $slide['image_url'] ?? $def['image_url'] ?? '') }}" maxlength="2048" placeholder="https://…">
                @error("hero_slides.$i.image_url")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field">
                <span>{{ __('Image file') }}</span>
                <input type="file" name="hero_slide_file[{{ $i }}]" accept="image/jpeg,image/png,image/webp,image/gif">
                @error("hero_slide_file.$i")<span class="error">{{ $message }}</span>@enderror
            </label>
            @if ($hasSlideFile)
                <input type="hidden" name="hero_slides[{{ $i }}][clear_image]" value="0">
                <label class="check">
                    <input type="checkbox" name="hero_slides[{{ $i }}][clear_image]" value="1" {{ old("hero_slides.$i.clear_image") === '1' ? 'checked' : '' }}>
                    <span>{{ __('Remove uploaded image for this banner') }}</span>
                </label>
            @endif
        </div>
    @endfor
</fieldset>
