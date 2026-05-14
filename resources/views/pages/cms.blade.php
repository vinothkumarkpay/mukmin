@extends('layouts.public')

@section('title', $page->title.' — '.$siteName)

@section('content')
    @if ($page->widgets_only)
        <h1 class="sr-only">{{ $page->title }}</h1>
        <div class="cms-widgets-stack">
            @forelse ($pageWidgets as $widget)
                @include('partials.cms-single-widget', ['widget' => $widget])
            @empty
                <div class="surface">
                    <p class="muted" style="margin:0">{{ __('This page is set to widgets only, but no active CMS widgets are linked yet. Add widgets in the admin (zone: CMS page, choose this page).') }}</p>
                </div>
            @endforelse
        </div>
    @else
        <article class="surface cms-page-article">
            <h1 class="page-title">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="muted" style="margin-top:0">{{ $page->excerpt }}</p>
            @endif
            <div class="cms-body">{!! $page->body !!}</div>
        </article>

        @if ($pageWidgets->isNotEmpty())
            <section class="cms-page-widgets-below" aria-label="{{ __('Related blocks') }}">
                @foreach ($pageWidgets as $widget)
                    <div style="margin-top:1rem">
                        @include('partials.cms-single-widget', ['widget' => $widget])
                    </div>
                @endforeach
            </section>
        @endif
    @endif
@endsection
