<?php

namespace App\Http\Middleware;

use App\Enums\Headers;
use App\Enums\HttpStatusCodes;
use App\Services\Auth\KeyTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $userId = $request->header(Headers::CLIENT_ID);
            if (! $userId) {
                return response()->json([
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => 'User not found'
                ], HttpStatusCodes::NOT_FOUND);
            }

            $keyStore = KeyTokenService::findKeyByUserID($userId);

            if (! $keyStore) {
                return response()->json([
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => 'Key not found' // public key
                ], HttpStatusCodes::NOT_FOUND);
            }

            if ($request->header(Headers::REFRESH_TOKEN)) {
                try {
                    $refreshToken = $request->header(Headers::REFRESH_TOKEN);
                    JWTAuth::setToken($refreshToken);
                    $decoded = JWTAuth::authenticate();

                    if ($decoded->id !== (int) $userId) {
                        return response()->json([
                            'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                            'message' => 'Unauthorized'
                        ], HttpStatusCodes::UNAUTHORIZED);
                    }

                    $request->keyStore = $keyStore;
                    $request->user = $decoded;
                    $request->refreshToken = $refreshToken;

                    return $next($request);
                } catch (\Exception $e) {
                    throw $e;
                }
            }

            $accessToken = $request->header(Headers::AUTHORIZATION);
            if (! $accessToken) {
                return response()->json([
                    'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                    'message' => 'Unauthorized'
                ], HttpStatusCodes::UNAUTHORIZED);
            }


            try {
                JWTAuth::setToken($accessToken);
                $decoded = JWTAuth::authenticate();

                if ($decoded->id !== (int) $userId) {
                    return response()->json([
                        'statusCode' => HttpStatusCodes::UNAUTHORIZED,
                        'message' => 'Unauthorized'
                    ], HttpStatusCodes::UNAUTHORIZED);
                }

                $request->keyStore = $keyStore;

                return $next($request);
            } catch (\Exception $e) {
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
