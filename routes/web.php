<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'active'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

        Route::view('categories', 'admin.categories.index')->name('categories.index');

        // Only a super admin can open these pages. Staff get a 403 page.
        Route::middleware('super_admin')->group(function () {
            Route::view('settings', 'admin.settings.edit')->name('settings.edit');
            Route::view('users', 'admin.users.index')->name('users.index');
        });

        Route::view('customers', 'admin.customers.index')->name('customers.index');
        Route::view('faqs', 'admin.faqs.index')->name('faqs.index');

        Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/create', [EnquiryController::class, 'create'])->name('enquiries.create');
        Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    });
});


Route::name('site.')->group(function () {
    Route::get('/', \App\Http\Controllers\Site\HomeController::class)->name('home');

    // The public site has no product pages and no prices. Old links go to the matching home page section.
    Route::redirect('/products', '/#products', 301)->name('shop');
    Route::redirect('/about', '/#about', 301)->name('about');
    Route::redirect('/contact', '/#contact', 301)->name('contact');

    Route::get('/faq', [\App\Http\Controllers\Site\PageController::class, 'faq'])->name('faq');
    Route::get('/sitemap.xml', \App\Http\Controllers\Site\SitemapController::class)->name('sitemap');

    Route::get('/enquiry', [\App\Http\Controllers\Site\EnquiryController::class, 'show'])->name('enquiry');
    Route::post('/enquiry', [\App\Http\Controllers\Site\EnquiryController::class, 'store'])->middleware('throttle:5,1')->name('enquiry.submit');
    Route::get('/enquiry/sent', [\App\Http\Controllers\Site\EnquiryController::class, 'thanks'])->name('enquiry.thanks');
});
