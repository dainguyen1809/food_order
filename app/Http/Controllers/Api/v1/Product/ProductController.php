<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Services\Product\ProductFactory;
use App\Services\Product\ProductStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function createProduct(CreateProductRequest $request)
    {
        $type = $request->product_type;
        $product_shop = $request->user()->id;
        $product_slug = str()->slug($request->product_name);
        $data = array_merge($request->all(), [
            'product_shop' => $product_shop,
            'product_slug' => $product_slug,
        ]);
        $metadata = ProductStrategy::createProduct($type, $data);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Created new product successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function getAllProductDrafts(Request $request)
    {
        return response()->json([
            'message' => 'Get data successfully',
            'metadata' => ProductStrategy::findAllProductDrafts($request->user()->id)
        ]);
    }

    public function getAllProductIsPublished(Request $request)
    {
        return response()->json([
            'message' => 'Get data successfully',
            'metadata' => ProductStrategy::getAllProductIsPublished($request->user()->id)
        ]);
    }

    public function productSearchByGuest(Request $request)
    {
        return response()->json([
            'metadata' => ProductStrategy::productSearchByGuest(Route::input('keySearch'))
        ]);
    }

    public function updateIsPublishedProduct(Request $request)
    {
        return response()->json([
            'message' => 'Column updated successfully',
            'metadata' => ProductStrategy::updateIsPublishedProduct($request->user()->id, Route::input('id'))
        ]);
    }

    public function updateUnPublishedProduct(Request $request)
    {
        return response()->json([
            'message' => 'Column updated successfully',
            'metadata' => ProductStrategy::updateUnPublishedProduct($request->user()->id, Route::input('id'))
        ]);
    }

}
