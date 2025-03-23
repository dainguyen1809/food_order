<?php

namespace App\Models\Repositories;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\CartProduct;

class CartRepository
{
    public static function getCartByID($cart_id)
    {
        return Cart::where('id', $cart_id)
            ->where('cart_status', CartStatus::ACTIVE)
            ->with(['cartProducts:cart_id,product_id,quantity,shop_id'])
            ->first();
    }

    public static function findCartOrCreate($user_id)
    {
        return Cart::firstOrCreate([
            'cart_user_id' => $user_id,
            'cart_status' => CartStatus::ACTIVE
        ]);
    }

    public static function checkCartExists($user_id)
    {
        return Cart::where('cart_user_id', $user_id)
            ->where('cart_status', CartStatus::ACTIVE)
            ->first();
    }

    public static function checkProductExistInCart($cart_id, $product)
    {
        return CartProduct::where('cart_id', $cart_id)
            ->where('product_id', $product['product_id'])
            ->first();
    }

    public static function insertItemInCart($cart_id, $product)
    {
        return CartProduct::create([
            'cart_id' => $cart_id,
            'product_id' => $product['product_id'],
            'quantity' => $product['quantity'],
            'shop_id' => $product['shop_id'],
        ]);
    }

    public static function getListCartItems($user_id)
    {
        return Cart::where('cart_user_id', $user_id)
            ->where('cart_status', CartStatus::ACTIVE)
            ->with(['cartProducts:cart_id,product_id,quantity,shop_id'])
            ->first();
    }

    public static function removeCartItem($cart_id, $product_id)
    {
        return CartProduct::where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->delete();
    }
}
