<?php

if (!function_exists('is_dev_env')) {
    function is_dev_env(): bool
    {
        return app()->environment(['local', 'development', 'testing']);
    }
}

if (!function_exists('is_production')) {
    function is_production(): bool
    {
        return !is_dev_env();
    }
}
