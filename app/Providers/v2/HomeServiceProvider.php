<?php

namespace App\Providers\v2;

use Illuminate\Support\ServiceProvider;
use App\Services\v2\HomeService2;

class HomeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(HomeService2::class, function($app){
            return new HomeService2();
        });
    }
}
