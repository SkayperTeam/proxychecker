<?php

namespace App\Providers;

use App\Http\Connectors\CheckerProxyConnector;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ProxyCheckerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /** @var array $config */
        $config = config('proxychecker');
        $this->app->scoped(CheckerProxyConnector::class, function (Application $app) use ($config) {
            $client = Http::baseUrl(
                Arr::get(
                    $config,
                    'guzzle.base_uri'
                )
            )
                ->withQueryParameters([
                    'apiKey' => $config['api_key'],
                    'lang' => 'ru'
                ]);

            return new CheckerProxyConnector($client);
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
