@extends('layouts.admin')

@section('title', __('New widget'))

@php($headerTitle = __('New widget'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('New widget') }}</h1>
        <form method="post" action="{{ route('admin.widgets.store') }}" class="form-grid" enctype="multipart/form-data">
            @csrf
            @include('admin.widgets.form', ['widget' => null, 'cmsPages' => $cmsPages])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.widgets.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
