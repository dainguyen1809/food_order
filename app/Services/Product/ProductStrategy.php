<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Repositories\ProductRepository;

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

    public static function updateProduct($type, $product_id, $payload)
    {
        $productClass = self::$productRegistry[$type];

        if (! class_exists($productClass)) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => "Class $productClass does not exist"
            ];
        }
        return (new $productClass($payload))->updateProduct($product_id, $payload);
    }


    public static function findAllProductDrafts($product_shop)
    {
        $query = [
            'product_shop' => $product_shop,
            'isDraft' => false // false
        ];

        return ProductRepository::getAllProductDrafts($query);
    }

    public static function getAllProductIsPublished($product_shop)
    {
        $query = [
            'product_shop' => $product_shop,
            'isPublished' => true
        ];

        return ProductRepository::getAllProductIsPublished($query);
    }

    public static function getAllProducts()
    {
        return ProductRepository::getAllProducts();
    }

    public static function productDetails($product_id)
    {
        return ProductRepository::productDetails($product_id);
    }

    public static function productSearchByGuest($keySearch)
    {
        return ProductRepository::productSearchByGuest($keySearch);
    }

    public static function updateIsPublishedProduct($product_shop, $product_id)
    {
        return ProductRepository::updateIsPublishedProduct($product_shop, $product_id);
    }

    public static function updateUnPublishedProduct($product_shop, $product_id)
    {
        return ProductRepository::updateUnPublishedProduct($product_shop, $product_id);
    }
}
