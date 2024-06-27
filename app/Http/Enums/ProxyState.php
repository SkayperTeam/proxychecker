<?php

namespace App\Http\Enums;

enum ProxyState: string
{
    case WORKING = 'working';
    case NOT_WORKING = 'not_working';
    case PROCESSING = 'processing';
}
