<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        return response()->json([
            'message' => 'Registered successfully',
            'metadata' => $this->service->register($request)
        ]);
    }
}
