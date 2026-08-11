<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\QontakWebhookController;
use App\Http\Controllers\WebsiteController;
use App\Http\Middleware\TrackPageView;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->middleware(TrackPageView::class)->name('home');
Route::get('/berita/{post:slug}', [WebsiteController::class, 'post'])->middleware(TrackPageView::class)->name('posts.show');
Route::post('/hubungi-kami', [WebsiteController::class, 'inquiry'])->middleware('throttle:6,1')->name('inquiries.store');
Route::post('/webhooks/qontak', QontakWebhookController::class)->middleware('throttle:120,1')->name('webhooks.qontak');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/statistik', AnalyticsController::class)->name('analytics');
        Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/akun', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/akun/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::resource('produk', AdminProductController::class)->parameters(['produk' => 'product']);
        Route::resource('berita', AdminPostController::class)->parameters(['berita' => 'post']);
        Route::get('/pesan', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/pesan/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('/pesan/{inquiry}', [AdminInquiryController::class, 'update'])->name('inquiries.update');
        Route::post('/pesan/{inquiry}/qontak', [AdminInquiryController::class, 'qontak'])->name('inquiries.qontak');
        Route::delete('/pesan/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
    });
});
