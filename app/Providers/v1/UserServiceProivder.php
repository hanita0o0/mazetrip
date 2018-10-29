<?php

namespace App\Providers\v1;

use App\Services\v1\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProivder extends ServiceProvider
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
        $this->app->bind(UserService::class, function($app){
           return new UserService();
        });
    }
}
