<?php

namespace App\Http\Resources;

use App\Models\ProxyGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProxyGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ProxyGroup $this */
        return [
            'id' => $this->id,
            'state' => $this->state,
            'proxies' => $this->proxies
        ];
    }
}
