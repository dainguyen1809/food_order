<?php

namespace App\Http\Middleware;

use App\Enums\Headers;
use App\Enums\HttpStatusCodes;
use App\Services\Auth\ApiKeyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $key = $request->header(Headers::API_KEY);

            if (! $key) {
                return response()->json([
                    'message' => 'Key not found!',
                    'statusCode' => HttpStatusCodes::FORBIDDEN
                ], HttpStatusCodes::FORBIDDEN);
            }

            $apiKey = ApiKeyService::findByID($key);

            if (! $apiKey) {
                return response()->json([
                    'message' => 'Forbidden error',
                    'statusCode' => HttpStatusCodes::FORBIDDEN
                ], HttpStatusCodes::FORBIDDEN);
            }

            $request->apiKey = $apiKey;

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
