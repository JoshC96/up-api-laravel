<?php

namespace App\Providers;

use App\Helpers\RequestHelper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(RequestHelper::class, function (Application $app) {
            return new RequestHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
