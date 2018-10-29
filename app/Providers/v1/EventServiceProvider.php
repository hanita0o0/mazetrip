<?php

namespace App\Providers\v1;

use App\Services\v1\EventService;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
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
        $this->app->bind(EventService::class, function($app){
            return new EventService();
        });
    }
}
