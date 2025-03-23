<?php

namespace App\Http\Controllers\Api\v1\Orders;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Orders\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkoutPreview(Request $request)
    {
        $metadata = CheckoutService::checkoutPreview($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'OK',
            'metadata' => $metadata
        ], $statusCode);
    }
}
