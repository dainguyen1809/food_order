<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;

class ProductStrategy
{
    static array $productRegistry = [];

    static function registerProductType($type, $classRef)
    {
        return self::$productRegistry[$type] = $classRef;
    }

    public static function createProduct($type, $data)
    {
        $productClass = self::$productRegistry[$type];

        if (! class_exists($productClass)) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => "Class $productClass does not exist"
            ];
        }
        return (new $productClass($data))->createProduct();
    }
}
