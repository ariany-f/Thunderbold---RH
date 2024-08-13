<?php

namespace App\Providers;

use App\Models\Employee;
use Illuminate\Support\ServiceProvider;
use App\Observers\EmployeeObserver;

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
        Employee::observe(EmployeeObserver::class);
    }
}
