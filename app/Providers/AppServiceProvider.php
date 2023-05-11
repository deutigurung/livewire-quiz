<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('admin',function(){
            // The ?-> operator is a null-safe dereferencing operator 
            // which checks if the left-hand side of the operator is not null, 
            // then calls the method is_admin on the user object
            return auth()->user()?->is_admin;
        });
    }
}
