<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createProduct($product_id = null)
    {
        return Product::create($this->data);
    }
}
