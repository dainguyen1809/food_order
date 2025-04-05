<?php

namespace App\Http\Controllers\Api\v1\Comment;

use App\Enums\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Services\Comments\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $request)
    {
        $metadata = CommentService::createComment($request->all());
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Comment created successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function getListComments(Request $request)
    {
        $metadata = CommentService::getListComments($request->productId, $request->parentId);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Comment created successfully',
            'metadata' => $metadata
        ], $statusCode);
    }

    public function deleteComment(Request $request)
    {
        $metadata = CommentService::deleteComment($request->productId, $request->commentId);
        $statusCode = $metadata['statusCode'] ?? HttpStatusCodes::CREATED;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $statusCode >= 400 ? 'Error' : 'Comment created successfully',
            'metadata' => $metadata
        ], $statusCode);
    }
}
