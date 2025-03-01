<?php

namespace App\Services\Contracts;

interface ProductServiceInterface
{
    public function createProduct($product_id);
    public function updateProduct($product_id, $payload);
}
