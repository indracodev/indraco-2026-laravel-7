<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        view()->composer(['partials.header', 'partials.menu_mobile'], \App\Http\View\Composers\CategoryComposer::class);
        view()->composer('layouts.app', \App\Http\View\Composers\SettingComposer::class);
    }
}
