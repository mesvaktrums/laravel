<?php

namespace App\Providers;
use App\Models\User; // Correct namespace for User model

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
    Schema::defaultStringLength(191); // Adjust the default string length
 User::observe(\App\Observers\UserObserver::class);
        //
    }
}
