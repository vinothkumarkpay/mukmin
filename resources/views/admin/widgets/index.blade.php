@extends('layouts.admin')

@section('title', __('Widgets'))

@php($headerTitle = __('Widgets'))

@section('content')
    <div class="stack-bar">
        <h1 class="page-title" style="margin:0">{{ __('Widgets') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.widgets.create') }}">{{ __('New widget') }}</a>
    </div>

    <p class="muted">{{ __('Zones: home (main page), header (below site nav), sidebar (reserved for future layouts), footer (site footer), cms (blocks inside a chosen CMS page below the page HTML).') }}</p>

    <div class="surface table-wrap" style="margin-top:1rem">
        <table class="data">
            <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Zone') }}</th>
                <th>{{ __('CMS page') }}</th>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Active') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($widgets as $widget)
                <tr>
                    <td>{{ $widget->title }}</td>
                    <td><code>{{ $widget->slug }}</code></td>
                    <td>{{ $widget->zone }}</td>
                    <td>
                        @if ($widget->zone === 'cms' && $widget->cmsPage)
                            <a href="{{ route('page.show', $widget->cmsPage->slug) }}" target="_blank" rel="noopener noreferrer">{{ $widget->cmsPage->title }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $widget->sort_order }}</td>
                    <td>{{ $widget->is_active ? __('Yes') : __('No') }}</td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-sm btn-ghost" href="{{ route('admin.widgets.edit', $widget) }}">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.widgets.destroy', $widget) }}" method="post" onsubmit="return confirm(@json(__('Delete this widget?')))">
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
        <div style="margin-top:.75rem">{{ $widgets->links('pagination::simple-default') }}</div>
    </div>
@endsection
