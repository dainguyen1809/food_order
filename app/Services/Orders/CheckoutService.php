<?php

namespace App\Services\Orders;

use App\Enums\HttpStatusCodes;
use App\Models\Repositories\CartRepository;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\CheckoutServiceInterface;
use App\Services\Discount\DiscountService;

class CheckoutService implements CheckoutServiceInterface
{
    public static function checkoutPreview($payload)
    {
        $cartID = $payload['cart_id'];
        $userID = $payload['user_id'];
        $shopOrderIds = $payload['shop_order_ids'] ?? [];

        $foundCart = CartRepository::getCartByID($cartID);
        if (! $foundCart) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Cart doesn't exist!"
            ];
        }

        $checkoutOrder = [
            'totalPrice' => 0,
            'feeShip' => 0,
            'totalDiscount' => 0,
            'totalAmount' => 0,
        ];
        $shopOrderIdsNew = []; // return new shop_order_ids after calculated

        foreach ($shopOrderIds as $index => $shopOrder) {
            // dump("[1]::Processing shopOrder index: ".$index);
            // dump([
            //     "[2]::" => $shopOrder
            // ]);

            $shopID = $shopOrder['shop_id'];
            $shopDiscount = $shopOrder['shop_discounts'] ?? [];
            $itemProducts = $shopOrder['item_products'] ?? [];

            // dump(['[item]' => $itemProducts]);

            foreach ($itemProducts as $item) {
                $productAvailable = ProductRepository::getProductAvailable($item['product_id']);

                // dump(['[1]:::' => $productAvailable]);

                if (! $productAvailable) {
                    return [
                        'statusCode' => HttpStatusCodes::NOT_FOUND,
                        'message' => "Product doesn't exist!"
                    ];
                }

                $productPrice = $productAvailable[0]->price ?? 0;
                $checkoutPrice = round($productPrice * $item['quantity'], 2);

                $checkoutOrder['totalPrice'] += $checkoutPrice;

                $itemCheckout = [
                    'shop_id' => $shopID,
                    'shop_discounts' => $shopDiscount,
                    'priceRaw' => $checkoutPrice,
                    'priceApplyDiscount' => $checkoutPrice,
                    'itemProduct' => $item
                ];

                // dump("[1]:::Discount: ", $shopDiscount);

                if (! empty($itemCheckout['shop_discounts'])) {
                    $getDiscount = DiscountService::userApplyDiscount([
                        'discount_code' => $shopDiscount[0]['discount_code'],
                        'user_id' => $userID,
                        'discount_shop' => $shopID,
                        'products' => collect($productAvailable) // closure function
                            ->map(function ($product) use ($item) { // loop through each element in the collection and return a new the collection
                                $product->quantity = $item['quantity']; // match qty from order item
                                return $product;
                            })
                    ]);

                    if (isset($getDiscount['statusCode']))
                        return $getDiscount; // Return error immediately

                    $discount = $getDiscount['total amount'];
                    $checkoutOrder['totalDiscount'] += $discount;

                    if ($discount > 0) {
                        $itemCheckout['priceApplyDiscount'] = round($checkoutPrice - $discount, 2);
                    }
                }

                $checkoutOrder['totalAmount'] = $checkoutOrder['totalAmount'] + $itemCheckout['priceApplyDiscount'];

                array_push($shopOrderIdsNew, $itemCheckout);
            }
        }
        return [
            'shop_order_ids' => $shopOrderIds,
            'new_shop_order_ids' => $shopOrderIdsNew,
            'checkout_order' => $checkoutOrder,
        ];
    }

}
