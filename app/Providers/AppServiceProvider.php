<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\QuoteManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('quotes', function ($app) {
            return new QuoteManager($app);
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
