<?php

namespace App\Models\Repositories;

use App\Enums\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public static function checkCartExist($user_id)
    {
        return Cart::where('cart_user_id', $user_id)->first();
    }

    public static function createCartForUser($user_id, $product)
    {
        $cart = Cart::updateOrCreate(
            [
                'cart_user_id' => $user_id,
                'cart_status' => CartStatus::ACTIVE,
            ],
            [
                'cart_products' => $product
            ]
        );

        $cart->increment('cart_count_product');

        return $cart;
    }

    public static function findCartItemByUserID($user_id, $product_id)
    {
        return Cart::where('cart_status', CartStatus::ACTIVE)
            ->whereJsonContains('cart_products', ['product_id' => $product_id])
            ->where('cart_user_id', $user_id)
            ->first();
    }
}
