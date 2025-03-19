<?php

namespace App\Services\Cart;

use App\Enums\CartStatus;
use App\Enums\HttpStatusCodes;
use App\Models\Cart;
use App\Models\Repositories\CartRepository;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\CartServiceInterface;

class CartService implements CartServiceInterface
{
    public static function addToCart($user_id, $product)
    {

        //valid product
        $foundProduct = ProductRepository::getProductByID($product['product_id']);
        if (! $foundProduct) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Product doesn't exists!"
            ];
        }

        // init cart user if doesn't exists
        $cartUser = CartRepository::findCartOrCreate($user_id);

        // check product exist in the cart
        $cart_id = $cartUser->id;
        $cartProduct = CartRepository::checkProductExistInCart($cart_id, $product);

        if ($cartProduct) {
            $cartProduct->increment('quantity', $product['quantity']);
        } else {
            return CartRepository::insertItemInCart($cart_id, $product);
        }

        return $cartUser->load('cartProducts');
    }

    public static function updateCart($user_id, $product)
    {
        $cart = CartRepository::checkCartExists($user_id);
        if (! $cart) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Cart not found'
            ];
        }

        // check product exist in the cart
        $cart_id = $cart->id;
        $cartProduct = CartRepository::checkProductExistInCart($cart_id, $product);

        if (! $cartProduct) {
            return self::addToCart($user_id, $product);
        }

        $foundProduct = ProductRepository::getProductByID($product['product_id']);
        if (! $foundProduct) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Product doesn't exists!"
            ];
        }

        if ($cartProduct->shop_id !== $product['shop_id']) {
            return [
                'statusCode' => HttpStatusCodes::CONFLICT,
                'message' => "This product do not belong to the shop!"
            ];
        }

        if ($cartProduct->quantity > 0) {
            $cartProduct->update([
                'quantity' => $product['quantity']
            ]);
        } else {
            // remove
        }

        return $cart->load('cartProducts');
    }

    public static function getListCartItems($user_id)
    {
        return CartRepository::getListCartItems($user_id);
    }

    public static function removeCartItem($user_id, $product)
    {
        $cart = CartRepository::checkCartExists($user_id);
        if (! $cart) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Cart not found'
            ];
        }

        $deleted = CartRepository::removeCartItem($cart->id, $product);

        // if item === 0 => delete cart
        if ($cart->cartProducts()->count() === 0) {
            $cart->delete();
        }

        return $deleted > 0;
    }
}
