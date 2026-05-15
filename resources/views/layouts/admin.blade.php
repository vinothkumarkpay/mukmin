<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Admin')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar admin-sidebar-desktop" aria-label="{{ __('Admin navigation') }}">
        <div class="logo">{{ config('app.name') }}</div>
        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
        <div class="nav-section">{{ __('Content') }}</div>
        <a href="{{ route('admin.site-configuration.edit') }}">{{ __('Site configuration') }}</a>
        <a href="{{ route('admin.menu-items.index') }}">{{ __('Menu items') }}</a>
        <a href="{{ route('admin.cms-pages.index') }}">{{ __('CMS pages') }}</a>
        <a href="{{ route('admin.widgets.index') }}">{{ __('Widgets') }}</a>
        
        <div class="nav-section">{{ __('Submissions') }}</div>
        <a href="{{ route('admin.submissions.contact.index') }}">{{ __('Contact Us') }}</a>
        <a href="{{ route('admin.submissions.registration.index') }}">{{ __('Registrations') }}</a>
        <a href="{{ route('admin.submissions.scholarship.index') }}">{{ __('Scholarships') }}</a>
        
        <div class="nav-section">{{ __('Site') }}</div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">{{ __('View website') }}</a>
        <form action="{{ route('logout') }}" method="post" style="margin-top:1rem">
            @csrf
            <button type="submit" class="btn btn-sm btn-ghost" style="width:100%;color:inherit;border-color:rgba(255,255,255,.35)">{{ __('Log out') }}</button>
        </form>
    </aside>

    <div class="admin-body">
        <div class="admin-topbar">
            <details class="admin-nav-drawer admin-mobile-nav">
                <summary>{{ __('Menu') }}</summary>
                <div class="stack">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    <a href="{{ route('admin.site-configuration.edit') }}">{{ __('Site configuration') }}</a>
                    <a href="{{ route('admin.menu-items.index') }}">{{ __('Menu items') }}</a>
                    <a href="{{ route('admin.cms-pages.index') }}">{{ __('CMS pages') }}</a>
                    <a href="{{ route('admin.widgets.index') }}">{{ __('Widgets') }}</a>
                    <hr>
                    <a href="{{ route('admin.submissions.contact.index') }}">{{ __('Contact Submissions') }}</a>
                    <a href="{{ route('admin.submissions.registration.index') }}">{{ __('Registrations') }}</a>
                    <a href="{{ route('admin.submissions.scholarship.index') }}">{{ __('Scholarships') }}</a>
                    <hr>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">{{ __('View website') }}</a>
                </div>
            </details>
            <div style="font-weight:700">{{ $headerTitle ?? __('Admin') }}</div>
            <div></div>
        </div>

        <div class="admin-content">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var targets = document.querySelectorAll('textarea.richtext');
    if (!targets.length) return;

    tinymce.init({
        selector: 'textarea.richtext',
        height: 400,
        menubar: true,
        promotion: false,
        branding: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'wordcount'
        ],
        toolbar: 'undo redo | styles | bold italic underline strikethrough | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | link image media table | ' +
                 'blockquote code removeformat fullscreen',
        style_formats: [
            { title: 'Heading 2', block: 'h2' },
            { title: 'Heading 3', block: 'h3' },
            { title: 'Heading 4', block: 'h4' },
            { title: 'Paragraph', block: 'p' },
            { title: 'Blockquote', block: 'blockquote' }
        ],
        images_upload_url: '{{ route("admin.upload-image") }}',
        automatic_uploads: true,
        images_reuse_filename: false,
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("admin.upload-image") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status === 422) {
                        var errors = JSON.parse(xhr.responseText);
                        var msg = errors.message || 'Validation failed';
                        reject({ message: msg, remove: true });
                        return;
                    }
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject({ message: 'Upload failed: HTTP ' + xhr.status, remove: true });
                        return;
                    }
                    var json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location !== 'string') {
                        reject({ message: 'Invalid response from server', remove: true });
                        return;
                    }
                    resolve(json.location);
                };

                xhr.onerror = function () {
                    reject({ message: 'Network error during upload', remove: true });
                };

                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', '{{ csrf_token() }}');
                xhr.send(formData);
            });
        },
        content_style: 'body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; font-size: 15px; color: #1a2e24; line-height: 1.6; padding: 0.5rem; } img { max-width: 100%; height: auto; border-radius: 8px; } a { color: #1f6b4a; }',
        convert_urls: false,
        relative_urls: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
});
</script>
@stack('scripts')
</body>
</html>
