<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('api', function ($app) {
            return new ApiManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
