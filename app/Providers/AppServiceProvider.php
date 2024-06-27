<?php

namespace App\Providers;

use App\Events\ProxyCreatedEvent;
use App\Events\ProxyUpdatedEvent;
use App\Listeners\CheckProxyGroupListener;
use App\Listeners\CheckProxyListener;
use Illuminate\Support\Facades\Event;
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
        Event::listen(
            ProxyCreatedEvent::class,
            CheckProxyListener::class
        );
        Event::listen(
            ProxyUpdatedEvent::class,
            CheckProxyGroupListener::class
        );
    }
}
