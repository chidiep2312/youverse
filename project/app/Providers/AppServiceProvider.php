<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

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
        Paginator::useBootstrap();
        $setting = Setting::first();
        View::share('setting', $setting);

        View::composer('*', function ($view) {
            $user = Auth::user();
            $view->with('notifications', $user ? $user->unreadNotifications : collect());
        });
    }
}
