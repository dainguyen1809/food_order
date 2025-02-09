<?php

namespace App\Services\Auth;

use App\Enums\HttpStatusCodes;
use App\Enums\RoleShop;
use App\Helpers\AuthHelper;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function register($request)
    {
        try {
            $holderUser = User::where('email', $request->email)->first();

            if ($holderUser) {
                return response()->json([
                    'statusCode' => HttpStatusCodes::FORBIDDEN,
                    'message' => 'Email already has been taken!'
                ], HttpStatusCodes::FORBIDDEN);
            }

            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);
            $newUser->roles = RoleShop::SHOP;
            $newUser->save();

            if ($newUser) {
                $publicKey = str()->random(64);
                $privateKey = str()->random(64);

                $keyStore = KeyTokenService::createKeyToken($newUser->id, $publicKey, $privateKey);

                if (! $keyStore) {
                    return response()->json([
                        'statusCode' => HttpStatusCodes::FORBIDDEN,
                        'message' => 'Key invalid!'
                    ]);
                }

                $tokens = AuthHelper::createTokensPair($newUser);

                return [
                    'statusCode' => 201,
                    'metadata' => [
                        'user' => $newUser->only(['id', 'name', 'email']),
                        'tokens' => $tokens
                    ]
                ];
            }

            return [
                'statusCode' => 200,
                'metadata' => null
            ];

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => HttpStatusCodes::BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }
}
