<?php

namespace App\Http\Controllers\Api\v1\Inventory;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function addStockToInventory(Request $request)
    {
        $metadata = InventoryService::addStockToInventory($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Stock updated successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
