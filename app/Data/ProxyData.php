<?php

namespace App\Data;

use App\Http\Enums\ProxyState;
use Spatie\LaravelData\Data;

class ProxyData extends Data
{
    public function __construct(
        public string $ip,
        public string|null $country,
        public string|null $city,
        public string|null $connection_type,
        public string|null $timeout,
        public ProxyState $state,
    ) {}

    public static function fromArray(array $data): ProxyData
    {
        return new self(
            (string) $data['ip'],
            (string) $data['country_name'],
            (string) $data['city'],
            (string) $data['connection_type'],
            (string) $data['timeout'],
            ProxyState::WORKING,
        );
    }
}
