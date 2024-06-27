<?php

namespace App\Models;

use App\Http\Enums\ProxyGroupState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property ProxyGroupState $state
 */
class ProxyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'state'
    ];

    protected $casts = [
        'state' => ProxyGroupState::class,
    ];

    public function proxies(): HasMany
    {
        return $this
            ->hasMany(Proxy::class, 'proxy_group_id');
    }
}
