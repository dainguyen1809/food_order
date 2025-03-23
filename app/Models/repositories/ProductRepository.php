<?php

namespace App\Models\Repositories;

use App\Models\Product;

class ProductRepository
{
    public static function getAllProductDrafts($query)
    {
        return self::productQuery($query, 'isDraft');
    }

    public static function getAllProductIsPublished($query)
    {
        return self::productQuery($query, 'isPublished');
    }

    public static function getAllProducts()
    {
        return Product::select([
            'product_name',
            'product_thumb',
            'product_price',
            'product_quantity',
        ])
            ->where('isPublished', true)
            ->orderBy('updated_at')
            ->paginate(50);
    }

    public static function productDetails($product_id)
    {
        return Product::where('id', $product_id)->firstOrFail();
    }

    public static function productSearchByGuest($keySearch)
    {
        $products = Product::where('isPublished', true)
            ->whereRaw("MATCH(product_name, product_description) AGAINST(? IN BOOLEAN MODE)", [$keySearch])
            ->orderBy('updated_at', 'desc')
            ->get();

        return $products;

    }

    public static function getProductByID($product_id)
    {
        return Product::where('id', $product_id)->where('isPublished', true)->first();
    }

    public static function getProductAvailable($product_id)
    {
        return Product::where('id', $product_id)
            ->where('isPublished', true)
            ->select([
                'id as product_id',
                'product_price as price',
                'product_quantity as quantity',
            ])->get();
    }


    public static function updateProductByID($product_id, $payload, $model)
    {
        $product = $model::where('id', $product_id)->firstOrFail();

        $product->update($payload);

        return $product->refresh();
    }

    public static function updateIsPublishedProduct($product_shop, $product_id)
    {
        $product = Product::where('product_shop', $product_shop)
            ->where('id', $product_id)
            ->first();
        if (! $product)
            return null;

        $product->update([
            'isDraft' => false,
            'isPublished' => true
        ]);

        return $product;
    }


    public static function updateUnPublishedProduct($product_shop, $product_id)
    {
        $product = Product::where('product_shop', $product_shop)
            ->where('id', $product_id)
            ->first();

        if (! $product)
            return null;

        $product->update([
            'isDraft' => true,
            'isPublished' => false
        ]);

        return $product;

    }


    private static function productQuery($query, $column)
    {
        return Product::with(['users:id,name,email'])
            ->where('product_shop', $query['product_shop'])
            ->where('isPublished', $query[$column])
            ->orderBy('updated_at', 'desc')
            ->paginate(50);
    }

}
