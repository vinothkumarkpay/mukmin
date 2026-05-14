@extends('layouts.admin')

@section('title', __('CMS pages'))

@php($headerTitle = __('CMS pages'))

@section('content')
    <div class="stack-bar">
        <h1 class="page-title" style="margin:0">{{ __('CMS pages') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.cms-pages.create') }}">{{ __('New page') }}</a>
    </div>

    <div class="surface table-wrap">
        <table class="data">
            <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Published') }}</th>
                <th>{{ __('Layout') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->title }}</td>
                    <td><code>{{ $page->slug }}</code></td>
                    <td>{{ $page->is_published ? __('Yes') : __('No') }}</td>
                    <td>{{ $page->widgets_only ? __('Widgets only') : __('HTML + widgets') }}</td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-sm btn-ghost" href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noopener noreferrer">{{ __('View') }}</a>
                            <a class="btn btn-sm btn-ghost" href="{{ route('admin.cms-pages.edit', $page) }}">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.cms-pages.destroy', $page) }}" method="post" onsubmit="return confirm(@json(__('Delete this page?')))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:.75rem">{{ $pages->links('pagination::simple-default') }}</div>
    </div>
@endsection
