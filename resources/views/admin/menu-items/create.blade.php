@extends('layouts.admin')

@section('title', __('New menu item'))

@php($headerTitle = __('New menu item'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('New menu item') }}</h1>
        <form method="post" action="{{ route('admin.menu-items.store') }}" class="form-grid">
            @csrf
            @include('admin.menu-items.form', ['item' => null])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.menu-items.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
