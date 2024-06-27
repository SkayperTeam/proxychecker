<?php

namespace App\Http\Controllers;

use App\Http\Actions\ProxyCheckerActon;
use App\Http\Enums\ProxyGroupState;
use App\Http\Requests\CheckProxyRequest;
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
            return response()->json(
                [
                    'message' => 'Проверка прокси успешно запущена',
                    'payload' => [
                        'proxy_group_id' => $action->run($request->input('proxies'))->id
                    ]
                ], 200
            );
        } catch (Throwable $e) {
            return response()->json(
                [
                    'message' => 'Ошибка: '.$e->getMessage(),
                    'payload' => []
                ], 422
            );
        }
    }

    public function checkProxyList(ProxyGroup $proxyGroup): JsonResponse
    {
        if ($proxyGroup->state === ProxyGroupState::FINISHED) {
            return response()->json(
                [
                    'status' => $proxyGroup->state,
                    'message' => 'Прокси успешно проверены',
                    'payload' => $proxyGroup->with('proxies')->get()
                ], 200
            );
        }

        return response()->json(
            [
                'status' => $proxyGroup->state,
                'message' => 'Прокси в обработке',
                'payload' => [],
            ], 200
        );
    }

    public function getArchiveProxy(): JsonResponse
    {
        return response()->json(
            [
                'message' => "Список архивных прокси",
                'payload' => ProxyGroup::query()
                    ->where('state', ProxyGroupState::FINISHED)
                    ->with(['proxies'])
                    ->get()
            ]
        );
    }


}
