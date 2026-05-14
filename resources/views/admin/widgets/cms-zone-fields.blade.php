@php
    $w = $widget ?? null;
    $s = optional($w)->settings ?? [];
    $curLayout = (string) ($s['cms_layout'] ?? 'html');
    $defaultTemplate = in_array($curLayout, ['html', 'lead'], true) ? $curLayout : 'custom';
    $template = old('cms_layout_template', $defaultTemplate);
@endphp

<fieldset class="js-cms-zone-only" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('CMS page block') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the zone is “CMS page”. Pick how this block renders on the public site.') }}</p>

    <label class="field">
        <span>{{ __('Layout type') }}</span>
        <select name="cms_layout_template" id="cms_layout_template">
            <option value="html" {{ $template === 'html' ? 'selected' : '' }}>{{ __('Simple HTML (title + content only)') }}</option>
            <option value="lead" {{ $template === 'lead' ? 'selected' : '' }}>{{ __('Lead block — eyebrow, headline, subheadline, optional buttons') }}</option>
            <option value="custom" {{ $template === 'custom' ? 'selected' : '' }}>{{ __('Other / advanced (keep existing layout settings)') }}</option>
        </select>
        @error('cms_layout_template')<span class="error">{{ $message }}</span>@enderror
    </label>

    <fieldset class="js-cms-lead-only" style="display:none;margin-top:1rem;padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius)">
        <legend style="font-weight:600;font-size:0.95rem">{{ __('Lead block copy') }}</legend>

        <label class="field">
            <span>{{ __('Image URL (optional — displayed alongside the text)') }}</span>
            <input type="text" name="cms_lead_image_url" value="{{ old('cms_lead_image_url', $s['image_url'] ?? '') }}" maxlength="2048" placeholder="https://images.unsplash.com/photo-...">
            @error('cms_lead_image_url')<span class="error">{{ $message }}</span>@enderror
            <span class="muted" style="font-weight:400">{{ __('Paste an image URL for a hero-style visual next to the text. Leave blank for a text-only block.') }}</span>
        </label>
        <label class="field">
            <span>{{ __('Eyebrow (small label above headline)') }}</span>
            <input type="text" name="cms_lead_eyebrow" value="{{ old('cms_lead_eyebrow', $s['eyebrow'] ?? '') }}" maxlength="500">
            @error('cms_lead_eyebrow')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Headline (main heading for this block)') }}</span>
            <input type="text" name="cms_lead_headline" value="{{ old('cms_lead_headline', $s['headline'] ?? '') }}" maxlength="500">
            @error('cms_lead_headline')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Subheadline') }}</span>
            <textarea name="cms_lead_subheadline" rows="2" maxlength="1000">{{ old('cms_lead_subheadline', $s['subheadline'] ?? '') }}</textarea>
            @error('cms_lead_subheadline')<span class="error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span>{{ __('Panel style') }}</span>
            @php
                $cmsLeadVariant = old('cms_lead_variant', $s['variant'] ?? 'surface');
            @endphp
            <select name="cms_lead_variant">
                <option value="surface" {{ $cmsLeadVariant === 'surface' || $cmsLeadVariant === '' ? 'selected' : '' }}>{{ __('Default card') }}</option>
                <option value="muted" {{ $cmsLeadVariant === 'muted' ? 'selected' : '' }}>{{ __('Muted background') }}</option>
                <option value="gradient" {{ $cmsLeadVariant === 'gradient' ? 'selected' : '' }}>{{ __('Gradient banner') }}</option>
            </select>
            @error('cms_lead_variant')<span class="error">{{ $message }}</span>@enderror
        </label>
        <input type="hidden" name="cms_lead_suppress_title" value="0">
        <label class="check">
            <input type="checkbox" name="cms_lead_suppress_title" value="1" {{ old('cms_lead_suppress_title', ! empty($s['suppress_title']) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <span>{{ __('Hide widget title above this block (use headline only)') }}</span>
        </label>

        <h3 style="margin:1rem 0 0.5rem;font-size:1rem">{{ __('Buttons (optional)') }}</h3>
        <p class="muted" style="margin:0 0 0.65rem;font-size:0.88rem">{{ __('Leave a row blank to skip. Use a path like /page/who-we-are or a full https:// URL.') }}</p>
        @foreach (range(0, \App\Models\Widget::CMS_LEAD_MAX_CTAS - 1) as $i)
            @php
                $cta = isset($s['ctas'][$i]) && is_array($s['ctas'][$i]) ? $s['ctas'][$i] : [];
                $oldBase = 'cms_lead_ctas.'.$i;
                $ctaStyle = old($oldBase.'.style', $cta['style'] ?? 'primary');
            @endphp
            <div style="padding:0.65rem;border:1px solid var(--color-border);border-radius:var(--radius);margin-bottom:0.5rem;background:rgba(0,0,0,0.02)">
                <span class="muted" style="font-size:0.82rem;font-weight:600">{{ __('Button') }} {{ $i + 1 }}</span>
                <label class="field">
                    <span>{{ __('Label') }}</span>
                    <input type="text" name="cms_lead_ctas[{{ $i }}][label]" value="{{ old($oldBase.'.label', $cta['label'] ?? '') }}" maxlength="120">
                    @error('cms_lead_ctas.'.$i.'.label')<span class="error">{{ $message }}</span>@enderror
                </label>
                <label class="field">
                    <span>{{ __('URL') }}</span>
                    <input type="text" name="cms_lead_ctas[{{ $i }}][url]" value="{{ old($oldBase.'.url', $cta['url'] ?? '') }}" maxlength="2048" placeholder="/page/our-ecosystem">
                    @error('cms_lead_ctas.'.$i.'.url')<span class="error">{{ $message }}</span>@enderror
                </label>
                <label class="field">
                    <span>{{ __('Style') }}</span>
                    <select name="cms_lead_ctas[{{ $i }}][style]">
                        <option value="primary" {{ $ctaStyle === 'primary' ? 'selected' : '' }}>{{ __('Primary') }}</option>
                        <option value="ghost" {{ $ctaStyle === 'ghost' ? 'selected' : '' }}>{{ __('Ghost / outline') }}</option>
                    </select>
                </label>
                <input type="hidden" name="cms_lead_ctas[{{ $i }}][new_tab]" value="0">
                <label class="check">
                    <input type="checkbox" name="cms_lead_ctas[{{ $i }}][new_tab]" value="1" {{ old($oldBase.'.new_tab', ! empty($cta['new_tab']) ? '1' : '0') === '1' ? 'checked' : '' }}>
                    <span>{{ __('Open in new tab') }}</span>
                </label>
            </div>
        @endforeach
    </fieldset>
</fieldset>

@push('scripts')
<script>
(function () {
    var zone = document.querySelector('select[name="zone"]');
    var cmsWrap = document.querySelector('.js-cms-zone-only');
    var layout = document.getElementById('cms_layout_template');
    var leadWrap = document.querySelector('.js-cms-lead-only');
    if (!zone || !cmsWrap || !layout || !leadWrap) return;

    function setDisabled(root, disabled) {
        root.querySelectorAll('input,textarea,select').forEach(function (el) {
            el.disabled = disabled;
        });
    }

    function sync() {
        var z = zone.value;
        var showCms = z === 'cms';
        cmsWrap.style.display = showCms ? 'block' : 'none';
        layout.disabled = !showCms;
        if (!showCms) {
            setDisabled(leadWrap, true);
            return;
        }
        var showLead = layout.value === 'lead';
        leadWrap.style.display = showLead ? 'block' : 'none';
        setDisabled(leadWrap, !showLead);
    }

    zone.addEventListener('change', sync);
    layout.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
