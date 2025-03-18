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
        // check cart exist
        $cartUser = CartRepository::checkCartExist($user_id);

        if (! $cartUser) {
            // create new cart for user
            $foundProduct = ProductRepository::getProductByID($product['product_id']);
            if (! $foundProduct) {
                return [
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => "Product doesn't exists!"
                ];
            }

            return self::createCartForUser($user_id, $product);
        }

        // cart exist but without item
        if (! empty($cartUser->cart_products)) {
            $cart = array_merge($cartUser->cart_products, $product);

            $updated = $cartUser->update();
        }

        // if cart exist and contains cart item
        return self::updateQtyCartItem($user_id, $product);
    }

    public static function createCartForUser($user_id, $product)
    {
        return CartRepository::createCartForUser($user_id, $product);
    }

    public static function updateQtyCartItem($user_id, $product)
    {
        $productID = $product['product_id'];
        $quantity = $product['quantity'];

        $foundCart = CartRepository::findCartItemByUserID($user_id, $productID);

        if ($foundCart) {
            $cartProducts = $foundCart->cart_products ?? [];
            $cartProducts['quantity'] = ($cartProducts['quantity'] ?? 0) + $quantity;

            // Set the modified array back
            $foundCart->cart_products = $cartProducts;
            $foundCart->save();

            return $foundCart;
        }

        return self::createCartForUser($user_id, $product);
    }

    /*
        {
            "user_id": 1809,
            "shop_orders_ids": [
            {
                "shop_id": "67ae13e3a2dbc857bd22b4ff",
                "item_products": [
                {
                    "product_id": "67b8482dad65634805d72a84",
                    "quantity": 5,
                    "old_quantity": 11,
                    "price": 149.99
                }
                ],
                "version": 2000
            }
            ]
        }
     */

    public static function updateCart($user_id, $shop_order_ids)
    {
        $obj = (object) $shop_order_ids[0];

        $data = [
            'product_id' => $obj->cart_item[0]['product_id'],
            'quantity' => $obj->cart_item[0]['quantity'],
            'old_quantity' => $obj->cart_item[0]['old_quantity'],
        ];

        $foundProduct = ProductRepository::getProductByID($data['product_id']);
        if (! $foundProduct) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Product doesn't exists!"
            ];
        }

        if ($foundProduct->product_shop !== $shop_order_ids[0]['shop_id']) {
            return [
                'statusCode' => HttpStatusCodes::CONFLICT,
                'message' => "This product do not belong to the shop!"
            ];
        }

        if ($data['quantity'] === 0) {
            // self::deleteCart
        }

        $product = [
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'] - $data['old_quantity']
        ];

        $updated = self::updateQtyCartItem($user_id, $product);
        return $updated;
    }

}
