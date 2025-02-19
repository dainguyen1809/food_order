<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    protected $product_name;
    protected $product_thumb;
    protected $product_description;
    protected $product_price;
    protected $product_quantity;
    protected $product_type;
    protected $product_shop;
    protected $product_attributes;

    public function __construct(array $data)
    {
        $this->product_name = $data['product_name'];
        $this->product_thumb = $data['product_thumb'];
        $this->product_description = $data['product_description'];
        $this->product_price = $data['product_price'];
        $this->product_quantity = $data['product_quantity'];
        $this->product_type = $data['product_type'];
        $this->product_shop = $data['product_shop'];
        $this->product_attributes = $data['product_attributes'];
    }

    public function createProduct()
    {
        return Product::create([
            'product_name' => $this->product_name,
            'product_thumb' => $this->product_thumb,
            'product_description' => $this->product_description,
            'product_price' => $this->product_price,
            'product_quantity' => $this->product_quantity,
            'product_type' => $this->product_type,
            'product_shop' => $this->product_shop,
            'product_attributes' => $this->product_attributes,
        ]);
    }
}
