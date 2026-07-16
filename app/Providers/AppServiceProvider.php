<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Tampilan project ini pakai Bootstrap 5, jadi pagination
        // juga perlu di-render dengan style Bootstrap (bukan default Tailwind).
        Paginator::useBootstrap();
    }
}
