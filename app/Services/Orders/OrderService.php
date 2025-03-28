<?php

namespace App\Services\Orders;

use App\Enums\HttpStatusCodes;
use App\Models\Repositories\OrderRepository;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Redis\LockingService;

class OrderService implements OrderServiceInterface
{
    private $locking;
    private $model;

    public function __construct(LockingService $locking, OrderRepository $model)
    {
        $this->locking = $locking;
        $this->model = $model;

    }

    public function orderByUSer($data)
    {
        $shopOrderIds = $data['shop_order_ids'];
        $userId = $data['user_id'];
        $cartId = $data['cart_id'];
        $userAddress = $data['user_address'] ?? [];
        $userPayment = $data['user_payment'] ?? [];

        $checkoutResult = CheckoutService::checkoutPreview([
            'user_id' => $userId,
            'cart_id' => $cartId,
            'shop_order_ids' => $shopOrderIds
        ]);

        if (isset($checkoutResult['statusCode']))
            return $checkoutResult; // Return error immediately

        $shopOrdersIdsNew = $checkoutResult['new_shop_order_ids'];
        $checkoutOrder = $checkoutResult['checkout_order'];

        $products = collect($shopOrdersIdsNew)
            ->map(function ($order) {
                return $order['itemProduct'] ?? [];
            })->filter()->values()->toArray();

        $acquireProduct = [];
        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            $keyLock = $this->locking->acquireLock($productId, $quantity, $cartId);
            $acquireProduct[] = $keyLock;

        }

        // if the product sold out (cannot locking)
        if (in_array(false, $acquireProduct, true)) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Some product have been updated. Please go back to your cart'
            ];
        }

        $newOrder = $this->model->createNewOrder([
            'order_userId' => $userId,
            'order_checkout' => $checkoutOrder,
            'order_shipping' => $userAddress,
            'order_payment' => $userPayment,
            'order_products' => $shopOrdersIdsNew,
        ]);

        return $newOrder;
    }
}
