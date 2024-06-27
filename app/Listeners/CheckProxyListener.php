<?php

namespace App\Listeners;

use App\Events\ProxyCreatedEvent;
use App\Jobs\CheckProxyJob;
use App\Models\Proxy;
use Illuminate\Contracts\Bus\Dispatcher;

class CheckProxyListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected Dispatcher $dispatcher,
    )
    {}

    /**
     * Handle the event.
     */
    public function handle(ProxyCreatedEvent $event): void
    {
        $proxy = $event->proxy;
        $this->dispatcher->dispatch(new CheckProxyJob($proxy));
    }
}
