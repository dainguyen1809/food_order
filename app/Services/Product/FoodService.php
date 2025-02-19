<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Food;

class FoodService extends ProductService
{
    public function createProduct()
    {
        $newFood = Food::create($this->product_attributes);
        if (! $newFood) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Error create new food'
            ];
        }

        $newProduct = parent::createProduct();
        if (! $newProduct) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Food:: Create new product error'
            ];
        }

        return $newProduct;
    }
}
