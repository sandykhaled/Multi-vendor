<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('filters',function($attribute,$value,$params){
            return !in_array(strtolower($value) , $params);
        },"Invalid Data");
        Paginator::useBootstrapFour();
//        Paginator::defaultView('pagination.custom');
    }
}
