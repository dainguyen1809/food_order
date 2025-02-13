<?php

namespace App\Services\Auth;

use App\Models\KeyToken;

class KeyTokenService
{
    public static function createKeyToken($userId, $publicKey, $privateKey, $refreshToken)
    {
        try {
            $tokens = KeyToken::updateOrCreate(
                ['user_id' => $userId,],
                [
                    'public_key' => $publicKey,
                    'private_key' => $privateKey,
                    'refresh_tokens_used' => [],
                    'refresh_token' => $refreshToken
                ]);

            return $tokens ? $tokens->public_key : null;

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function findKeyByUserID($userId)
    {
        $userID = KeyToken::where('id', $userId)->first();

        return $userID;
    }

    public static function deleteKeyByID($id)
    {
        return KeyToken::destroy($id);
    }

}
