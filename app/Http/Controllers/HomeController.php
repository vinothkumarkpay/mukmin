<?php

namespace App\Http\Controllers;

use App\Models\Widget;

class HomeController extends Controller
{
    public function index()
    {
        $homeWidgets = Widget::query()
            ->active()
            ->zone('home')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('pages.home', [
            'homeWidgets' => $homeWidgets,
        ]);
    }
}
