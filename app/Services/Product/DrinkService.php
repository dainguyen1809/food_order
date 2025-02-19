<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Drink;

class DrinkService extends ProductService
{
    public function createProduct()
    {
        $newDrink = Drink::create($this->product_attributes);
        if (! $newDrink) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Error create new drink'
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
