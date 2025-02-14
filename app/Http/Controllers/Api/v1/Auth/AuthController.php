<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->refreshToken;
        $keyStore = $request->keyStore;
        $user = $request->user;

        return response()->json([
            'metadata' => $this->service->refresh($keyStore, $user, $refreshToken),
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json([
            'statusCode' => HttpStatusCodes::OK,
            'message' => 'Logout success',
            'metadata' => $this->service->logout($request->keyStore),
        ], HttpStatusCodes::OK);
    }

    public function login(Request $request)
    {
        $metadata = $this->service->login($request);

        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'metadata' => $metadata,
        ], $statusCode);
    }

    public function register(RegisterRequest $request)
    {
        return response()->json([
            'message' => 'Registered successfully',
            'code' => HttpStatusCodes::CREATED,
            'metadata' => $this->service->register($request)
        ], HttpStatusCodes::CREATED);
    }
}
