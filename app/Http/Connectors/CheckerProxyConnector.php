<?php

namespace App\Http\Connectors;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class CheckerProxyConnector
{
    protected PendingRequest $httpClient;

    public function __construct(PendingRequest $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws ConnectionException
     */
    public function checkProxy(string $proxy): PromiseInterface|Response
    {
        return $this
            ->httpClient
            ->withQueryParameters([
                'ip' => $proxy,
//                'lang' => 'ru'
                'fields' => 'country_name,city,connection_type'
            ])
            ->get('/');
    }


}
