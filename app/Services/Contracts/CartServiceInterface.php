<?php

namespace App\Services\Contracts;

interface CartServiceInterface
{
    public static function addToCart($user_id, $product);
    public static function createCartForUser($user_id, $product);
    public static function updateQtyCartItem($user_id, $product);
    public static function updateCart($user_id, $product);
}
