<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createProduct($product_id)
    {
        return Product::create($this->data);
    }

    public function updateProduct($product_id, $payload)
    {
        if ($payload['product_rating'] < 5 && $payload['product_rating'] > 1) {
            return ProductRepository::updateProductByID($product_id, $payload, $model = Product::class);
        }

        return [
            'statusCode' => 400,
            'message' => 'Product Rating must be greater than 1 and less than 5'
        ];
    }

}
