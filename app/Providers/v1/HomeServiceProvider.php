<?php

namespace App\Providers\v1;

use App\Services\v1\HomeService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(HomeService::class, function($app){
            return new HomeService();
        });
    }
}
