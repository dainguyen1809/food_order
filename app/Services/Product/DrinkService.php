<?php

namespace App\Services\Product;

use App\Enums\HttpStatusCodes;
use App\Models\Drink;
use App\Models\Repositories\ProductRepository;

class DrinkService extends ProductService
{
    public function createProduct($product_id = null)
    {
        $newProduct = parent::createProduct($product_id);
        if (! $newProduct) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Food:: Create new product error'
            ];
        }

        $newDrink = Drink::create(array_merge($this->data['product_attributes'], ['id' => $newProduct['id']]));
        if (! $newDrink) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Error create new drink'
            ];
        }

        return $newProduct;
    }

    public function updateProduct($product_id, $payload)
    {
        if (isset($payload['product_attributes'])) {
            ProductRepository::updateProductByID(
                $product_id,
                nestedArrayParser($payload['product_attributes']),
                Drink::class
            );
        }

        $updated = parent::updateProduct($product_id, $payload);
        return $updated;
    }

}
