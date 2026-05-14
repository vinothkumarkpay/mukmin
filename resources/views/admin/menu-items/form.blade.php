@php($item = $item ?? null)
@php($parentOptions = $parentOptions ?? collect())
<label class="field">
    <span>{{ __('Parent (optional)') }}</span>
    <select name="parent_id">
        <option value="">{{ __('— Top-level menu —') }}</option>
        @foreach ($parentOptions as $p)
            <option value="{{ $p->id }}" {{ (string) old('parent_id', optional($item)->parent_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
        @endforeach
    </select>
    @error('parent_id')<span class="error">{{ $message }}</span>@enderror
    <span class="muted" style="font-weight:400">{{ __('Submenus are one level: only top-level items can be parents.') }}</span>
</label>
<label class="field">
    <span>{{ __('Label') }}</span>
    <input type="text" name="label" value="{{ old('label', optional($item)->label) }}" required maxlength="255">
    @error('label')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('URL') }}</span>
    <input type="text" name="url" value="{{ old('url', optional($item)->url) }}" required maxlength="2048" placeholder="/page/about or https://…">
    @error('url')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Sort order') }}</span>
    <input type="number" name="sort_order" value="{{ old('sort_order', optional($item)->sort_order ?? 0) }}" min="0" max="999999">
    @error('sort_order')<span class="error">{{ $message }}</span>@enderror
</label>
<input type="hidden" name="is_active" value="0">
<label class="check">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', (optional($item)->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <span>{{ __('Visible in navigation') }}</span>
</label>
<input type="hidden" name="open_new_tab" value="0">
<label class="check">
    <input type="checkbox" name="open_new_tab" value="1" {{ old('open_new_tab', (optional($item)->open_new_tab ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <span>{{ __('Open in new tab') }}</span>
</label>
