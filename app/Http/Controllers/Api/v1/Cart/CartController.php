<?php

namespace App\Http\Controllers\Api\v1\Cart;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $metadata = CartService::addToCart($request->user_id, $request->product);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Created new discount successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function updateCart(Request $request)
    {

        $metadata = CartService::updateCart($request->user_id, $request->shop_order_ids);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Created new discount successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
