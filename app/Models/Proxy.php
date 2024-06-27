<?php

namespace App\Models;

use App\Events\ProxyCreatedEvent;
use App\Events\ProxyUpdatedEvent;
use App\Http\Enums\ProxyState;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $proxy_group_id
 * @property string|null $connection_type
 * @property string $ip
 * @property string $port
 * @property string|null $timeout
 * @property string|null $country
 * @property string|null $city
 * @property string|null $state
 * @property string|null $ip_with_port
 * @property-read ProxyGroup $group
 * @mixin Model
 */
class Proxy extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => ProxyCreatedEvent::class,
        'updated' => ProxyUpdatedEvent::class,
    ];
    protected $fillable = [
        'proxy_group_id',
        'connection_type',
        'ip',
        'port',
        'timeout',
        'country',
        'city',
        'state',
        'errors',
    ];

    protected $casts = [
        'state' => ProxyState::class,
        'errors' => 'array'
    ];

    public function group(): BelongsTo
    {
        return $this
            ->belongsTo(ProxyGroup::class, 'proxy_group_id');
    }

    public function ipWithPort(): Attribute
    {
        return Attribute::make(
            fn (): string => "{$this->ip}:{$this->port}"
        );
    }
}
