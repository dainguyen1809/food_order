<?php

namespace App\Http\Controllers\Api\v1\Discount;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\CreateDiscountRequest;
use App\Services\Discount\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DiscountController extends Controller
{
    public function createNewDiscount(CreateDiscountRequest $request)
    {
        $metadata = DiscountService::createNewDiscount($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Created new discount successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function updateDiscountByID(Request $request)
    {
        $metadata = DiscountService::updateDiscountByID(Route::input('discount_id'), $request->user()->id, $request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Discount updated successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function discountDetails(Request $request)
    {
        $metadata = DiscountService::discountDetails(Route::input('discount_id'));
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Get data successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function getListDiscountWithProduct(Request $request)
    {
        $metadata = DiscountService::getListDiscountWithProduct(basename($_SERVER['REQUEST_URI']));
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Get data successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function getListDiscountByShop(Request $request)
    {
        $metadata = DiscountService::getListDiscountByShop($request->user()->id);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Get data successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function userApplyDiscount(Request $request)
    {
        $metadata = DiscountService::userApplyDiscount($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Discount codes applied successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function userCanceledDiscount(Request $request)
    {
        $metadata = DiscountService::userCanceledDiscount($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Discount canceled successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function deleteDiscountByID(Request $request)
    {
        $metadata = DiscountService::deleteDiscountByID(Route::input('discount_id'), $request->user()->id);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Discount deleted successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

}
