@extends('layouts.admin')

@section('title', __('Edit widget'))

@php($headerTitle = __('Edit widget'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('Edit widget') }}</h1>
        <form method="post" action="{{ route('admin.widgets.update', $widget) }}" class="form-grid" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.widgets.form', ['widget' => $widget, 'cmsPages' => $cmsPages])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.widgets.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
