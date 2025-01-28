<?php

namespace App\Services\Contracts;

use App\Http\Requests\Auth\RegisterRequest;

interface AuthServiceInterface
{
    public function register(RegisterRequest $request);
    // public function login();
}
