<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Laravel now uses utf8mb4 by default for columns and a huge default limit for
         * varchar's in SQL, this causes problems with slightly out-of-date versions of
         * MySQL and MariaDB.
         */
        Schema::defaultStringLength(191);
        
        Blade::if("accessLevel", function($targetLevel) {
            if (Auth::user() == null || !array_key_exists($targetLevel, \App\User::ACCESS_LEVELS)) {
                return false;
            }
            
            return Auth::user()->access_level === \App\User::ACCESS_LEVELS[$targetLevel];
        });
    }
}
