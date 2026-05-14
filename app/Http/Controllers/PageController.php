<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = CmsPage::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $pageWidgets = $page->inPageWidgets()
            ->where('is_active', true)
            ->get();

        return view('pages.cms', [
            'page' => $page,
            'pageWidgets' => $pageWidgets,
        ]);
    }
}
