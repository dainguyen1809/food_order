<?php

namespace App\Services\Contracts;

interface CheckoutServiceInterface
{
    public static function checkoutPreview($payload);
}
