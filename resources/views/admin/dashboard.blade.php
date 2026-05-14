@extends('layouts.admin')

@section('title', __('Dashboard'))

@php($headerTitle = __('Dashboard'))

@section('content')
    <div class="surface">
        <h1 class="page-title" style="margin-top:0">{{ __('Dashboard') }}</h1>
        <p class="muted">{{ __('Signed in as :name.', ['name' => auth()->user()->name]) }}</p>

        <ul class="muted" style="margin-top:1rem;line-height:1.7">
            <li>{{ __('Menu items: :n', ['n' => $menuCount]) }}</li>
            <li>{{ __('CMS pages: :n', ['n' => $pageCount]) }}</li>
            <li>{{ __('Widgets: :n', ['n' => $widgetCount]) }}</li>
            <li>{{ __('Site name: :n', ['n' => $siteName]) }}</li>
        </ul>
    </div>
@endsection
