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
                'public_key' => $publicKey,
                'private_key' => $privateKey,
            ]);

            return $tokens ? $tokens->public_key : null;

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
