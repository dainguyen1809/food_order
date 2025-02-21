<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Services\Product\ProductFactory;
use App\Services\Product\ProductStrategy;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct(CreateProductRequest $request)
    {
        $type = $request->product_type;
        $product_shop = $request->user()->id;
        $data = array_merge($request->all(), ['product_shop' => $product_shop]);
        $metadata = ProductStrategy::createProduct($type, $data);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Created new product successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
