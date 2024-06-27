<?php

use App\Http\Controllers\ProxyCheckerController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::controller(ProxyCheckerController::class)
    ->prefix('proxy-checker')
    ->group(function (Router $router) {
        $router->post('/', 'checkProxy');
        $router->get('/{proxyGroup}', 'checkProxyList');
        $router->get('/archive', 'getArchiveProxy');
    });
