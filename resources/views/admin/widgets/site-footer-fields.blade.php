@php($s = optional($widget)->settings ?? [])
@php($defs = \App\Models\Widget::defaultSiteFooterSettings())
@php($defCols = $defs['footer_columns'] ?? [])
@php($defSoc = $defs['footer_social'] ?? [])
@php($iconKeys = \App\Models\Widget::siteFooterSocialIconKeys())

<fieldset class="js-site-footer-only site-footer-fieldset" style="display:none;margin-top:1rem;padding:1rem;border:1px solid var(--color-border);border-radius:var(--radius);background:var(--color-bg)">
    <legend style="font-weight:700;padding:0 0.35rem">{{ __('Site footer (columns & social)') }}</legend>
    <p class="muted" style="margin:0 0 1rem;font-size:0.9rem">{{ __('Shown when the slug is “site-footer” and zone is “footer”. Use paths like /page/about or full https URLs. Use # as a placeholder.') }}</p>

    <label class="field">
        <span>{{ __('Copyright line (after site name)') }}</span>
        <input type="text" name="footer_copyright_suffix" value="{{ \App\Models\Widget::decodeFooterText((string) (old('footer_copyright_suffix') ?? $s['footer_copyright_suffix'] ?? $defs['footer_copyright_suffix'] ?? '')) }}" maxlength="500" placeholder="{{ __('Crafted for impact.') }}">
        @error('footer_copyright_suffix')<span class="error">{{ $message }}</span>@enderror
        <span class="muted" style="font-weight:400">{{ __('Appears as: © year · site name · this text') }}</span>
    </label>

    @for ($c = 0; $c < \App\Models\Widget::SITE_FOOTER_MAX_COLUMNS; $c++)
        @php($col = isset($s['footer_columns'][$c]) && is_array($s['footer_columns'][$c]) ? $s['footer_columns'][$c] : [])
        @php($dc = $defCols[$c] ?? ['title' => '', 'links' => []])
        <div style="padding:0.85rem;border:1px dashed var(--color-border);border-radius:var(--radius);margin-top:0.75rem;background:rgba(0,0,0,0.02)">
            <h3 style="margin:0 0 0.65rem;font-size:1rem">{{ __('Column') }} {{ $c + 1 }}</h3>
            <label class="field">
                <span>{{ __('Column heading') }}</span>
                <input type="text" name="footer_columns[{{ $c }}][title]" value="{{ \App\Models\Widget::decodeFooterText((string) (old("footer_columns.$c.title") ?? $col['title'] ?? $dc['title'] ?? '')) }}" maxlength="120">
                @error("footer_columns.$c.title")<span class="error">{{ $message }}</span>@enderror
            </label>
            @for ($l = 0; $l < \App\Models\Widget::SITE_FOOTER_MAX_LINKS_PER_COLUMN; $l++)
                @php($link = isset($col['links'][$l]) && is_array($col['links'][$l]) ? $col['links'][$l] : [])
                @php($dl = isset($dc['links'][$l]) && is_array($dc['links'][$l]) ? $dc['links'][$l] : [])
                <div style="display:grid;gap:0.5rem;margin-top:0.5rem;padding:0.5rem;border-radius:var(--radius);background:rgba(0,0,0,0.03)">
                    <span class="muted" style="font-size:0.85rem">{{ __('Link') }} {{ $l + 1 }}</span>
                    <label class="field" style="margin:0">
                        <span>{{ __('Label') }}</span>
                        <input type="text" name="footer_columns[{{ $c }}][links][{{ $l }}][label]" value="{{ \App\Models\Widget::decodeFooterText((string) (old("footer_columns.$c.links.$l.label") ?? $link['label'] ?? $dl['label'] ?? '')) }}" maxlength="200">
                        @error("footer_columns.$c.links.$l.label")<span class="error">{{ $message }}</span>@enderror
                    </label>
                    <label class="field" style="margin:0">
                        <span>{{ __('URL') }}</span>
                        <input type="text" name="footer_columns[{{ $c }}][links][{{ $l }}][url]" value="{{ old("footer_columns.$c.links.$l.url", $link['url'] ?? $dl['url'] ?? '') }}" maxlength="2048" placeholder="#">
                        @error("footer_columns.$c.links.$l.url")<span class="error">{{ $message }}</span>@enderror
                    </label>
                    <input type="hidden" name="footer_columns[{{ $c }}][links][{{ $l }}][new_tab]" value="0">
                    <label class="check">
                        <input type="checkbox" name="footer_columns[{{ $c }}][links][{{ $l }}][new_tab]" value="1" {{ (string) old("footer_columns.$c.links.$l.new_tab", ($link['new_tab'] ?? $dl['new_tab'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
                        <span>{{ __('Open in new tab') }}</span>
                    </label>
                </div>
            @endfor
        </div>
    @endfor

    <h3 style="margin:1.25rem 0 0.65rem;font-size:1rem">{{ __('Social buttons') }}</h3>
    <p class="muted" style="margin:0 0 0.75rem;font-size:0.9rem">{{ __('Leave URL empty to hide a slot. Rows with a URL show an icon button.') }}</p>
    @for ($i = 0; $i < \App\Models\Widget::SITE_FOOTER_MAX_SOCIAL; $i++)
        @php($soc = isset($s['footer_social'][$i]) && is_array($s['footer_social'][$i]) ? $s['footer_social'][$i] : [])
        @php($ds = $defSoc[$i] ?? ['icon' => 'link', 'url' => '', 'label' => '', 'new_tab' => false])
        @php($ic = old("footer_social.$i.icon", $soc['icon'] ?? $ds['icon'] ?? 'link'))
        <div style="display:grid;gap:0.5rem;margin-top:0.5rem;padding:0.65rem;border:1px dashed var(--color-border);border-radius:var(--radius);background:rgba(0,0,0,0.02)">
            <span class="muted" style="font-size:0.85rem">{{ __('Social') }} {{ $i + 1 }}</span>
            <label class="field" style="margin:0">
                <span>{{ __('Icon') }}</span>
                <select name="footer_social[{{ $i }}][icon]">
                    @foreach ($iconKeys as $key)
                        <option value="{{ $key }}" {{ $ic === $key ? 'selected' : '' }}>{{ $key === 'x' ? 'X' : ucfirst($key) }}</option>
                    @endforeach
                </select>
                @error("footer_social.$i.icon")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field" style="margin:0">
                <span>{{ __('URL') }}</span>
                <input type="text" name="footer_social[{{ $i }}][url]" value="{{ old("footer_social.$i.url", $soc['url'] ?? $ds['url'] ?? '') }}" maxlength="2048" placeholder="#">
                @error("footer_social.$i.url")<span class="error">{{ $message }}</span>@enderror
            </label>
            <label class="field" style="margin:0">
                <span>{{ __('Accessibility label') }}</span>
                <input type="text" name="footer_social[{{ $i }}][label]" value="{{ \App\Models\Widget::decodeFooterText((string) (old("footer_social.$i.label") ?? $soc['label'] ?? $ds['label'] ?? '')) }}" maxlength="120" placeholder="{{ __('Facebook') }}">
                @error("footer_social.$i.label")<span class="error">{{ $message }}</span>@enderror
            </label>
            <input type="hidden" name="footer_social[{{ $i }}][new_tab]" value="0">
            <label class="check">
                <input type="checkbox" name="footer_social[{{ $i }}][new_tab]" value="1" {{ (string) old("footer_social.$i.new_tab", ($soc['new_tab'] ?? $ds['new_tab'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
                <span>{{ __('Open in new tab') }}</span>
            </label>
        </div>
    @endfor
</fieldset>

@push('scripts')
<script>
(function () {
    var slug = document.querySelector('input[name="slug"]');
    var blocks = document.querySelectorAll('.js-site-footer-only');
    if (!slug || !blocks.length) return;
    function sync() {
        var v = (slug.value || '').trim().toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-|-$/g, '');
        var show = (v === 'site-footer') ? 'block' : 'none';
        blocks.forEach(function (el) { el.style.display = show; });
    }
    slug.addEventListener('input', sync);
    slug.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
