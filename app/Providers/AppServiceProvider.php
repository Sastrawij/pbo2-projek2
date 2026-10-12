<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination memakai tampilan Bootstrap 5 (sesuai AdminLTE 4)
        Paginator::useBootstrapFive();
    }
}
