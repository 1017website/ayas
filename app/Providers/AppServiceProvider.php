<?php

namespace App\Providers;

use App\Models\Inquiry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            $view->with(
                'sidebarNewCount',
                request()->user()?->isHeadAdmin() ? Inquiry::query()->where('status', 'new')->count() : 0
            );
        });
    }
}
