<?php

namespace App\Listeners;

use App\Events\ProxyUpdatedEvent;
use App\Http\Enums\ProxyGroupState;
use App\Http\Enums\ProxyState;

class CheckProxyGroupListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProxyUpdatedEvent $event): void
    {
        $proxy = $event->proxy;
        $proxyGroup = $proxy->group;
        $proxyGroup
            ->proxies()
            ->where(
                'state',
                ProxyState::PROCESSING
            )
            ->count() !== 0
            ? $proxyGroup->update(['state' => ProxyGroupState::FINISHED])
            : null;
    }
}
