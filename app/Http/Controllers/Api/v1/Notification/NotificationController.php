<?php

namespace App\Http\Controllers\Api\v1\Notification;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getListNotifyByUser(Request $request)
    {
        $metadata = NotificationService::getListNotifyByUser($request->userId ?? 1, $request->type, $request->isRead);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::OK;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Get data successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
