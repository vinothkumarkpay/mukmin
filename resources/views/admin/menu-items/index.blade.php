@extends('layouts.admin')

@section('title', __('Menu items'))

@php($headerTitle = __('Menu items'))

@section('content')
    <div class="stack-bar">
        <h1 class="page-title" style="margin:0">{{ __('Menu items') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.menu-items.create') }}">{{ __('Add item') }}</a>
    </div>

    @if ($errors->has('menu'))
        <p class="error" style="margin:0 0 1rem">{{ $errors->first('menu') }}</p>
    @endif

    <div class="surface table-wrap">
        <table class="data">
            <thead>
            <tr>
                <th>{{ __('Label') }}</th>
                <th>{{ __('Parent') }}</th>
                <th>{{ __('URL') }}</th>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Active') }}</th>
                <th>{{ __('New tab') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        @if ($item->parent_id)
                            <span class="muted" style="margin-right:0.25rem" aria-hidden="true">↳</span>
                        @endif
                        {{ $item->label }}
                    </td>
                    <td>{{ $item->parent ? $item->parent->label : '—' }}</td>
                    <td style="word-break:break-all">{{ $item->url }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td>{{ $item->is_active ? __('Yes') : __('No') }}</td>
                    <td>{{ $item->open_new_tab ? __('Yes') : __('No') }}</td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-sm btn-ghost" href="{{ route('admin.menu-items.edit', $item) }}">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.menu-items.destroy', $item) }}" method="post" onsubmit="return confirm(@json(__('Remove this menu item?')))">
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
        <div style="margin-top:.75rem">{{ $items->links('pagination::simple-default') }}</div>
    </div>
@endsection
