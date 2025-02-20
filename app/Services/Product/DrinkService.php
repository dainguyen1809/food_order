<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Drink;

class DrinkService extends ProductService
{
    public function createProduct($product_id = null)
    {
        $newDrink = Drink::create(array_merge($this->data['product_attributes'], ['id' => $product_id]));
        if (! $newDrink) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Error create new drink'
            ];
        }

        $newProduct = parent::createProduct($newDrink->id);
        if (! $newProduct) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Food:: Create new product error'
            ];
        }

        return $newProduct;
    }
}
