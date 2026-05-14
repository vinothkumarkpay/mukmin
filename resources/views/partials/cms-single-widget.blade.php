@php
    $layout = (string) data_get($widget->settings ?? [], 'cms_layout', 'html');
    if ($layout === '') {
        $layout = 'html';
    }
    $tpl = 'widgets.cms.'.$layout;
@endphp
@if (\Illuminate\Support\Facades\View::exists($tpl))
    @include($tpl, ['widget' => $widget])
@else
    <section class="surface cms-page-widget cmsw-html">
        @if (trim((string) $widget->title) !== '')
            <h2 class="page-title" style="font-size:1.15rem;margin-bottom:.5rem">{{ $widget->title }}</h2>
        @endif
        <div class="cms-body">{!! $widget->content !!}</div>
    </section>
@endif
