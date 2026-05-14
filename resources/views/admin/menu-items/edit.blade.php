@extends('layouts.admin')

@section('title', __('Edit menu item'))

@php($headerTitle = __('Edit menu item'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('Edit menu item') }}</h1>
        <form method="post" action="{{ route('admin.menu-items.update', $item) }}" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.menu-items.form', ['item' => $item])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                <a class="btn btn-ghost" href="{{ route('admin.menu-items.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
