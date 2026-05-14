<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\Widget;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'menuCount' => MenuItem::query()->count(),
            'pageCount' => CmsPage::query()->count(),
            'widgetCount' => Widget::query()->count(),
            'siteName' => SiteSetting::get('site_name', config('app.name')),
        ]);
    }
}
