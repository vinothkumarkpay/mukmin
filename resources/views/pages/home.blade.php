@extends('layouts.public')

@section('title', $siteName)

@section('content')
    @php($hasHomeHero = $homeWidgets->where('slug', 'home-hero')->isNotEmpty())

    @unless ($hasHomeHero)
        <div class="surface">
            <h1 class="page-title">{{ __('Welcome') }}</h1>
            <p class="muted">{{ __('This portal is powered by the CMS. Manage content from the admin area.') }}</p>
        </div>
    @endunless

    @forelse ($homeWidgets as $widget)
        @if ($widget->slug === 'home-hero')
            <div class="home-hero-fullbleed" role="presentation">
                @include('widgets.home-hero', ['widget' => $widget])
            </div>
        @elseif ($widget->slug === \App\Models\Widget::HOME_IMPACT_STATS_SLUG)
            <div class="home-impact-stats-wrap">
                @include('widgets.home-impact-stats', ['widget' => $widget])
            </div>
        @elseif ($widget->slug === \App\Models\Widget::HOME_VOICES_SLUG)
            <div class="home-voices-wrap">
                @include('widgets.home-voices', ['widget' => $widget])
            </div>
        @elseif ($widget->slug === \App\Models\Widget::HOME_JOIN_MOVEMENT_SLUG)
            @include('widgets.home-join-movement', ['widget' => $widget])
        @else
            <div class="surface widget-zone" style="margin-top:1rem">
                <h2 class="page-title" style="font-size:1.2rem">{{ $widget->title }}</h2>
                <div class="cms-body">{!! $widget->content !!}</div>
            </div>
        @endif
    @empty
        <p class="muted" style="margin-top:1rem">{{ __('No home widgets yet. Add widgets with zone “home” in the admin.') }}</p>
    @endforelse
@endsection
