<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function findUserByEmail($email);
}
