@extends('layouts.admin')

@section('title', __('Site configuration'))

@php($headerTitle = __('Site configuration'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('Site configuration') }}</h1>
        <p class="muted">{{ __('Site name is always shown to visitors. When a logo image is set, the name is used as alternative text for that image.') }}</p>

        <form method="post" action="{{ route('admin.site-configuration.update') }}" class="form-grid" style="margin-top:1rem" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="field">
                <span>{{ __('Site name') }}</span>
                <input type="text" name="site_name" value="{{ old('site_name', $siteName) }}" required maxlength="255">
                @error('site_name')<span class="error">{{ $message }}</span>@enderror
            </label>

            <label class="field">
                <span>{{ __('Logo image') }}</span>
                <input type="file" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp">
                @error('site_logo')<span class="error">{{ $message }}</span>@enderror
                <span class="muted" style="font-weight:400">{{ __('JPEG, PNG, GIF, or WebP. Max 2 MB. Uploaded file is shown on the public site; external URL is used only when no file is stored.') }}</span>
            </label>

            @if ($hasUploadedLogo)
                <input type="hidden" name="remove_uploaded_logo" value="0">
                <label class="check">
                    <input type="checkbox" name="remove_uploaded_logo" value="1" {{ old('remove_uploaded_logo') === '1' ? 'checked' : '' }}>
                    <span>{{ __('Remove uploaded logo file') }}</span>
                </label>
            @endif

            <label class="field">
                <span>{{ __('External logo URL (optional)') }}</span>
                <input type="url" name="site_logo_url" value="{{ old('site_logo_url', $siteLogoUrlField) }}" maxlength="2048" placeholder="https://…">
                @error('site_logo_url')<span class="error">{{ $message }}</span>@enderror
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>

        <p class="muted" style="margin-top:1.5rem">{{ __('Preview') }}</p>
        <div class="admin-logo-preview">
            @if ($logoPreviewUrl)
                <img src="{{ $logoPreviewUrl }}" alt="{{ $siteName }}">
            @else
                <p class="muted" style="margin:0">{{ __('No image — visitors will see the site name only.') }}</p>
            @endif
        </div>
    </div>
@endsection
