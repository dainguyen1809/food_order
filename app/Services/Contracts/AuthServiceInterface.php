<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function register($request);
    public function login($request);
    public function logout($request);
}
