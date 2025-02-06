<?php

namespace App\Services\Auth;

use App\Models\KeyToken;

class KeyTokenService
{
    public static function createKeyToken($userId, $publicKey, $privateKey)
    {
        try {
            $tokens = KeyToken::create([
                'user_id' => $userId,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ]);

            return $tokens ? $tokens->publicKey : null;

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
