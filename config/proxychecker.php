<?php

declare(strict_types=1);

return [
    'api_key' => env('PROXY_СHECKER_API_KEY'),
    'guzzle' => [
        'base_uri' => env('PROXY_CHECKER_URL'),
//        'timeout' => env('SMSCRU_TIMEOUT', 5),
//        'connect_timeout' => env('SMSCRU_CONNECT_TIMEOUT', 5),
    ],
];
