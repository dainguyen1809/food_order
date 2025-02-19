<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;

class ProductFactory
{
    public static function createProduct($type, $data)
    {
        switch ($type) {
            case 'food':
                return (new FoodService($data))->createProduct();
            case 'drink':
                return (new DrinkService($data))->createProduct();
            default:
                return [
                    'statusCode' => HttpStatusCodes::BAD_REQUEST,
                    'message' => "Create product type invalid: $type"
                ];
        }
    }
}
