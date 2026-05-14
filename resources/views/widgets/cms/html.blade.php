@php($s = $widget->settings ?? [])
<section class="surface cms-page-widget cmsw-html">
    @unless (data_get($s, 'suppress_title'))
        @if (trim((string) $widget->title) !== '')
            <h2 class="page-title cmsw-html__title">{{ $widget->title }}</h2>
        @endif
    @endunless
    @if (trim((string) $widget->content) !== '')
        <div class="cms-body cmsw-html__body">{!! $widget->content !!}</div>
    @endif
</section>
