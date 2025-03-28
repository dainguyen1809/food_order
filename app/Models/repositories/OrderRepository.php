<?php

namespace App\Models\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function createNewOrder($data)
    {
        return Order::create([
            'order_userId' => $data['order_userId'],
            'order_checkout' => $data['order_checkout'],
            'order_shipping' => $data['order_shipping'],
            'order_payment' => $data['order_payment'],
            'order_products' => $data['order_products']
        ]);
    }
}
