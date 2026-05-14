<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\Widget;
use App\Support\SiteBranding;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $publicLayoutData = function ($view) {
            $view->with([
                'siteName' => SiteSetting::get('site_name', config('app.name')),
                'siteLogoUrl' => SiteBranding::logoUrlForDisplay(),
                'headerMenuItems' => MenuItem::query()
                    ->where('is_active', true)
                    ->whereNull('parent_id')
                    ->with([
                        'children' => function ($q) {
                            $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                        },
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get(),
                'headerWidgets' => Widget::query()
                    ->active()
                    ->zone('header')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get(),
                'footerWidgets' => Widget::query()
                    ->active()
                    ->zone('footer')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get(),
            ]);
        };

        // Child views render before the layout; they need the same data for @section('title', …).
        View::composer(['layouts.public', 'pages.home', 'pages.cms'], $publicLayoutData);
    }
}
