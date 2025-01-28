<?php

namespace App\Services;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthService implements AuthServiceInterface
{
    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return $user;
    }
}
