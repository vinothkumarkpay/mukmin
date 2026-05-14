@php($page = $page ?? null)
<label class="field">
    <span>{{ __('Slug') }}</span>
    <input type="text" name="slug" value="{{ old('slug', optional($page)->slug) }}" required maxlength="255" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" placeholder="about-us">
    @error('slug')<span class="error">{{ $message }}</span>@enderror
    <span class="muted" style="font-weight:400">{{ __('Public URL: /page/your-slug') }}</span>
</label>
<label class="field">
    <span>{{ __('Title') }}</span>
    <input type="text" name="title" value="{{ old('title', optional($page)->title) }}" required maxlength="255">
    @error('title')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Excerpt') }}</span>
    <input type="text" name="excerpt" value="{{ old('excerpt', optional($page)->excerpt) }}" maxlength="500">
    @error('excerpt')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Body (HTML allowed)') }}</span>
    <textarea name="body" class="code" rows="14">{{ old('body', optional($page)->body) }}</textarea>
    @error('body')<span class="error">{{ $message }}</span>@enderror
    <span class="muted" style="font-weight:400">{{ __('Ignored on the public site when “Widgets only” is enabled below.') }}</span>
</label>

<input type="hidden" name="widgets_only" value="0">
<label class="check">
    <input type="checkbox" name="widgets_only" value="1" {{ old('widgets_only', (optional($page)->widgets_only ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <span>{{ __('Widgets only (public page shows widget blocks only — no title, excerpt, or HTML body)') }}</span>
</label>
<p class="muted" style="margin:0 0 0.5rem;font-size:0.85rem">{{ __('Attach blocks under Admin → Widgets, zone “CMS page”, and pick this page. Sort order controls stacking.') }}</p>

<input type="hidden" name="is_published" value="0">
<label class="check">
    <input type="checkbox" name="is_published" value="1" {{ old('is_published', optional($page)->is_published ? '1' : '0') === '1' ? 'checked' : '' }}>
    <span>{{ __('Published') }}</span>
</label>
