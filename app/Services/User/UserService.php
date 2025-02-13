<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
