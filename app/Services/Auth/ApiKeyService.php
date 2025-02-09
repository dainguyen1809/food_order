<?php

namespace App\Services\Auth;

use App\Models\ApiKey;

class ApiKeyService
{
    public static function findByID($key)
    {
        $apiKey = ApiKey::where('key', $key)
            ->where('status', 1)
            ->limit(1)
            ->first();

        return $apiKey;
    }
}
