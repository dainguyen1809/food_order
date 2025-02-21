<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Food;

class FoodService extends ProductService
{
    public function createProduct($product_id = null)
    {
        $newFood = Food::create(array_merge($this->data['product_attributes'], ['id' => $product_id]));
        if (! $newFood) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Error create new food'
            ];
        }

        $newProduct = parent::createProduct($newFood->id);
        if (! $newProduct) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Food:: Create new product error'
            ];
        }

        return $newProduct;
    }
}
