<?php

namespace App\Services\Auth;

use App\Enums\Headers;
use App\Enums\HttpStatusCodes;
use App\Enums\RoleShop;
use App\Helpers\AuthHelper;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\KeyToken;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    public function refresh($keyStore, $user, $refreshToken)
    {
        if (in_array($refreshToken, $keyStore->refresh_tokens_used ?? [])) {
            KeyTokenService::deleteKeyByUserID($user->id);
            return [
                'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                'message' => 'Something wrong!! Please re-login'
            ];
        }

        if ($keyStore->refresh_token !== $refreshToken) {
            return [
                'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                'metadata' => 'Unauthorized'
            ];
        }

        $foundUser = $this->service->findUserByEmail($user->email);
        if (! $foundUser) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'metadata' => 'User not found!'
            ];
        }

        $tokens = AuthHelper::createTokensPair($user);

        $usedTokens = $keyStore->refresh_tokens_used ?? [];
        $usedTokens[] = $refreshToken;

        $usedTokens = [];
        $usedTokens[] = $refreshToken; // add new refresh_token to array
        $keyStore->refresh_tokens_used = $usedTokens;
        $keyStore->save();


        KeyToken::where('user_id', $user->id)
            ->update([
                'refresh_token' => $tokens['refreshToken'],
                'refresh_tokens_used' => $usedTokens,
            ]);

        return [$user, $tokens];

    }

    public function logout($request)
    {
        $key = KeyTokenService::deleteKeyByID($request->id);
        return $key;
    }

    public function login($request)
    {
        try {
            $user = $this->service->findUserByEmail($request->email);
            if (! $user) {
                return [
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => 'User not found!'
                ];
            }

            $match = Hash::check($request->password, $user->password);

            if (! $match) {
                return [
                    'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                    'metadata' => 'Your email or password invalid!'
                ];
            }

            $publicKey = str()->random(64);
            $privateKey = str()->random(64);


            $tokens = AuthHelper::createTokensPair($user);

            $keyStore = KeyTokenService::createKeyToken($user->id, $publicKey, $privateKey, $tokens['refreshToken']);

            return [
                'statusCode' => HttpStatusCodes::OK,
                'user' => $user->only(['id', 'name', 'email', 'roles']),
                'tokens' => $tokens,
            ];

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => HttpStatusCodes::BAD_REQUEST,
                'message' => $e->getMessage()
            ]);
        }
    }


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

                $tokens = AuthHelper::createTokensPair($newUser);

                $keyStore = KeyTokenService::createKeyToken($newUser->id, $publicKey, $privateKey, $tokens['refreshToken']);

                if (! $keyStore) {
                    return response()->json([
                        'statusCode' => HttpStatusCodes::FORBIDDEN,
                        'message' => 'Key invalid!'
                    ]);
                }

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
