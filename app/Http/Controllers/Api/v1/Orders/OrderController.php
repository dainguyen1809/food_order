<?php

namespace App\Http\Controllers\Api\v1\Orders;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $service;
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function orderByUSer(Request $request)
    {
        $metadata = $this->service->orderByUSer($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Order successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
