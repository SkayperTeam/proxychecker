<?php

namespace App\Http\Enums;

enum ProxyGroupState: string
{
    case PROCESSING = 'processing';
    case FINISHED = 'finished';
}
