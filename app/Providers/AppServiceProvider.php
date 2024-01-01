<?php

namespace App\Providers;

use App\Repository\EventRepository;
use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $wikimedia = new WikimediaEventSource(config('services.wikimedia.access_token'));
        $this->app->singleton(WikimediaEventSource::class, function () use ($wikimedia) {
            return $wikimedia;
        });

        $this->app->singleton(EventRepository::class, function () use ($wikimedia) {
            return new EventRepository($wikimedia);
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
