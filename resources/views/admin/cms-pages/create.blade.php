@extends('layouts.admin')

@section('title', __('New CMS page'))

@php($headerTitle = __('New CMS page'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('New CMS page') }}</h1>
        <form method="post" action="{{ route('admin.cms-pages.store') }}" class="form-grid">
            @csrf
            @include('admin.cms-pages.form', ['page' => null])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.cms-pages.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
