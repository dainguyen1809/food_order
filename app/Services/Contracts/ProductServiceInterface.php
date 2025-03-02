<?php

namespace App\Services\Contracts;

interface ProductServiceInterface
{
    public function createProduct();
    public function updateProduct($product_id, $payload);
}
