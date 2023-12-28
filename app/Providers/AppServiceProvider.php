<?php

namespace App\Providers;

use App\Wikimedia\Wikimedia;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Wikimedia::class, function ($app) {
            return new Wikimedia(config('services.wikimedia.access_token'));
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
