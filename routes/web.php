<?php

use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\SiteConfigurationController;
use App\Http\Controllers\Admin\WidgetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/site-configuration', [SiteConfigurationController::class, 'edit'])->name('site-configuration.edit');
    Route::put('/site-configuration', [SiteConfigurationController::class, 'update'])->name('site-configuration.update');

    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::resource('cms-pages', CmsPageController::class)->except(['show']);
    Route::resource('widgets', WidgetController::class)->except(['show']);

    Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload-image');
});
