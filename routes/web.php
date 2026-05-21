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

use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\Admin\SubmissionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Public Form Routes
Route::get('/contact-us', [FormSubmissionController::class, 'showContactForm'])->name('contact.show');
Route::post('/contact-us', [FormSubmissionController::class, 'submitContactForm'])->name('contact.submit');

Route::get('/register', [FormSubmissionController::class, 'showMembershipSelection'])->name('register.show');
Route::get('/register/ordinary', [FormSubmissionController::class, 'showRegistrationForm'])->name('register.ordinary.show');
Route::post('/register/ordinary', [FormSubmissionController::class, 'submitRegistrationForm'])->name('register.ordinary.submit');
Route::get('/register/friends', [FormSubmissionController::class, 'showFriendsForm'])->name('register.friends.show');
Route::post('/register/friends', [FormSubmissionController::class, 'submitFriendsForm'])->name('register.friends.submit');

Route::get('/apply-scholarship', [FormSubmissionController::class, 'showScholarshipForm'])->name('scholarship.show');
Route::post('/apply-scholarship', [FormSubmissionController::class, 'submitScholarshipForm'])->name('scholarship.submit');

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
    
    // Submissions
    Route::get('/submissions/contact', [SubmissionController::class, 'contactIndex'])->name('submissions.contact.index');
    Route::get('/submissions/contact/{submission}', [SubmissionController::class, 'contactShow'])->name('submissions.contact.show');
    
    Route::get('/submissions/registration', [SubmissionController::class, 'registrationIndex'])->name('submissions.registration.index');
    Route::get('/submissions/registration/{submission}', [SubmissionController::class, 'registrationShow'])->name('submissions.registration.show');
    
    Route::get('/submissions/scholarship', [SubmissionController::class, 'scholarshipIndex'])->name('submissions.scholarship.index');
    Route::get('/submissions/scholarship/{submission}', [SubmissionController::class, 'scholarshipShow'])->name('submissions.scholarship.show');
    
    Route::get('/submissions/friends', [SubmissionController::class, 'friendsIndex'])->name('submissions.friends.index');
    Route::get('/submissions/friends/{submission}', [SubmissionController::class, 'friendsShow'])->name('submissions.friends.show');
});
