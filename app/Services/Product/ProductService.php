<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Repositories\InventoryRepository;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createProduct()
    {
        $shopID = $this->data['product_shop'];
        $stock = $this->data['product_quantity'];
        $newProduct = Product::create($this->data);

        if ($newProduct) {
            InventoryRepository::insertInventory($newProduct->id, $shopID, $stock);
        }

        return $newProduct;
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
