<?php

namespace App\Http\Controllers;

use App\Http\Actions\ProxyCheckerActon;
use App\Http\Enums\ProxyGroupState;
use App\Http\Requests\CheckProxyRequest;
use App\Http\Resources\ProxyGroupResource;
use App\Http\Responses\FromApi;
use App\Models\ProxyGroup;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class ProxyCheckerController extends Controller
{
    /**
     * @throws Exception
     */
    public function checkProxy(CheckProxyRequest $request, ProxyCheckerActon $action): JsonResponse
    {
        try {
            return FromApi::success($action->run($request->input('proxies')));
        } catch (Throwable $e) {
            return FromApi::error('Ошибка: '.$e->getMessage(), 422);
        }
    }

    public function checkProxyList(ProxyGroup $proxyGroup): JsonResponse
    {
        return FromApi::success(
            new ProxyGroupResource($proxyGroup)
        );
    }

    public function getArchiveProxy(): JsonResponse
    {
        return FromApi::success(
            ProxyGroup::query()
                ->where('state', ProxyGroupState::FINISHED)
                ->with(['proxies'])
                ->get()
        );
    }


}
