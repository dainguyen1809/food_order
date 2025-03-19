<?php

namespace App\Services\Contracts;

interface CartServiceInterface
{
    public static function addToCart($user_id, $product);
    public static function updateCart($user_id, $product);
    public static function getListCartItems($user_id);
    public static function removeCartItem($user_id, $product);
}
