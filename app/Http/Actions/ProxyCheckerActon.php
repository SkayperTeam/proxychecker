<?php

namespace App\Http\Actions;

use App\Http\Connectors\CheckerProxyConnector;
use App\Http\Enums\ProxyGroupState;
use App\Http\Enums\ProxyState;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProxyCheckerActon
{

    public function __construct(
        protected CheckerProxyConnector $connector,
        protected Dispatcher $dispatcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function run(string|array $proxies): ProxyGroup
    {
        if (!is_array($proxies)) {
            $proxies = explode(' ', str_replace(["\r", "\n"], ' ', $proxies));
        }

        try {
            return DB::transaction(function () use ($proxies): ProxyGroup {
               /** @var ProxyGroup $proxyGroup */
               $proxyGroup = ProxyGroup::query()
                   ->create([
                       'state' => ProxyGroupState::PROCESSING,
                   ]);

               foreach ($proxies as $proxy) {
                   list($ip, $port) = explode(':', $proxy);

                   /** @var Proxy $proxyModel */
                   $proxyGroup->proxies()->create([
                       'ip' => $ip,
                       'port' => $port,
                       'state' => ProxyState::PROCESSING,
                   ]);
               }

               return $proxyGroup;
           });
        } catch (Throwable $e) {
            logger()->error('Ошибка при проверке прокси', [$e]);

            abort(422, 'Ошибка при проверке прокси: ' . $e->getMessage());
        }
    }
}
