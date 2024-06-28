<?php

namespace App\Jobs;

use App\Data\ProxyData;
use App\Http\Connectors\CheckerProxyConnector;
use App\Models\Proxy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CheckProxyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Proxy $proxy
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(CheckerProxyConnector $connector): void
    {
        try {
            $start = microtime(true);
            $response = $connector->checkProxy($this->proxy->ip_with_port);
            $end = microtime(true);

            /** @var array $proxyInfo */
            $proxyInfo = json_decode($response, true);
            $proxyInfo['timeout'] = round(($end - $start) * 1000, 2);

            $data = ProxyData::fromArray(json_decode($response, true));

            $this
                ->proxy
                ->update($data->toArray());
        } catch (Throwable $e) {
            $this
                ->proxy
                ->update([
                    'errors' => [$e->getMessage()],
                ]);
        }

    }
}
