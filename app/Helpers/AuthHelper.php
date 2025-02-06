<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthHelper
{
    public static function createTokensPair($user)
    {
        $accessToken = JWTAuth::fromUser($user);
        $customClaims = [
            'exp' => now()->addDays(7)->timestamp,
            'type' => 'refresh'
        ];

        $refreshToken = JWTAuth::claims($customClaims)->fromUser($user);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }
}
