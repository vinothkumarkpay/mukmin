@php($widget = $widget ?? null)
@php($cmsPages = $cmsPages ?? collect())
<label class="field">
    <span>{{ __('Slug') }}</span>
    <input type="text" name="slug" value="{{ old('slug', optional($widget)->slug) }}" required maxlength="255" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" placeholder="welcome-banner">
    @error('slug')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Title') }}</span>
    <input type="text" name="title" value="{{ old('title', optional($widget)->title) }}" required maxlength="255">
    @error('title')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Zone') }}</span>
    <select name="zone">
        @foreach (['home' => __('Home'), 'header' => __('Header'), 'sidebar' => __('Sidebar'), 'footer' => __('Footer'), 'cms' => __('CMS page (in content)')] as $value => $label)
            <option value="{{ $value }}" {{ old('zone', optional($widget)->zone ?? 'home') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('zone')<span class="error">{{ $message }}</span>@enderror
    <span class="muted" style="font-weight:400">{{ __('“CMS page” blocks appear below the HTML body on the matching published page (/page/slug).') }}</span>
</label>

<label class="field" id="widget-cms-page-field">
    <span>{{ __('CMS page') }}</span>
    <select name="cms_page_id">
        <option value="">{{ __('— Select a page —') }}</option>
        @foreach ($cmsPages as $p)
            <option value="{{ $p->id }}" {{ (string) old('cms_page_id', optional($widget)->cms_page_id) === (string) $p->id ? 'selected' : '' }}>
                {{ $p->title }} ({{ $p->slug }})
            </option>
        @endforeach
    </select>
    @error('cms_page_id')<span class="error">{{ $message }}</span>@enderror
</label>
<label class="field">
    <span>{{ __('Content (HTML allowed)') }}</span>
    <textarea name="content" class="code" rows="10">{{ old('content', optional($widget)->content) }}</textarea>
    @error('content')<span class="error">{{ $message }}</span>@enderror
    <span class="muted" style="font-weight:400">{{ __('For “home-hero”, put headline and body copy here; appearance and banners use the sections below. For “home-impact-stats”, “home-voices”, and “home-join-movement”, headings and CTAs use the sections below — this field is optional. For “site-footer”, use the footer section below — content here is optional.') }}</span>
</label>

@include('admin.widgets.hero-appearance', ['widget' => $widget])
@include('admin.widgets.hero-slides', ['widget' => $widget])
@include('admin.widgets.impact-stats-fields', ['widget' => $widget])
@include('admin.widgets.voices-fields', ['widget' => $widget])
@include('admin.widgets.join-movement-fields', ['widget' => $widget])
@include('admin.widgets.site-footer-fields', ['widget' => $widget])
<label class="field">
    <span>{{ __('Sort order') }}</span>
    <input type="number" name="sort_order" value="{{ old('sort_order', optional($widget)->sort_order ?? 0) }}" min="0" max="999999">
    @error('sort_order')<span class="error">{{ $message }}</span>@enderror
</label>
<input type="hidden" name="is_active" value="0">
<label class="check">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', (optional($widget)->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
    <span>{{ __('Active') }}</span>
</label>
