@extends('layouts.admin')

@section('title', __('Edit CMS page'))

@php($headerTitle = __('Edit CMS page'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('Edit CMS page') }}</h1>
        <form method="post" action="{{ route('admin.cms-pages.update', $page) }}" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.cms-pages.form', ['page' => $page])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.cms-pages.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
