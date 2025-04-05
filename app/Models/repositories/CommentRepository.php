<?php

namespace App\Models\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public static function findMaxIdxRight($productId)
    {
        return Comment::where('comment_productId', $productId)
            ->orderBy('comment_right', 'desc')
            ->first();
    }

    public static function createComment($data, $idxRight)
    {
        $comment = new Comment();
        $comment->comment_left = $idxRight;
        $comment->comment_right = $idxRight + 1;
        $comment->comment_productId = $data['productId'];
        $comment->comment_userId = $data['userId'];
        $comment->comment_content = $data['content'];
        $comment->comment_parentId = $data['parentId'] ?? null;
        $comment->save();

        return $comment;
    }

    public static function getCommentById($commentId)
    {
        return Comment::select(
            'id',
            'comment_productId',
            'comment_userId',
            'comment_content',
            'comment_left',
            'comment_right',
            'comment_parentId',
        )->find($commentId);
    }

    public static function findParentComment($parentId)
    {
        return Comment::select(
            'id',
            'comment_productId',
            'comment_userId',
            'comment_content',
            'comment_left',
            'comment_right',
            'comment_parentId',
        )->find($parentId);
    }

    public static function updateIdxComment($productId, $idxRight)
    {
        $right = Comment::where('comment_productId', $productId)
            ->where('comment_right', '>=', $idxRight)
            ->increment('comment_right', 2);

        $left = Comment::where('comment_productId', $productId)
            ->where('comment_left', '>', $idxRight)
            ->increment('comment_left', 2);

        return [
            'idx_right' => $right,
            'idx_left' => $left,
        ];
    }

    public static function getListComments($productId, $idxLeft, $idxRight)
    {
        return Comment::where('comment_productId', $productId)
            ->where('comment_right', '<=', $idxRight)
            ->where('comment_left', '>', $idxLeft)
            ->paginate(50);
    }

    public static function getParentComment($productId, $parentId)
    {
        return Comment::where('comment_productId', $productId)
            ->where('comment_parentId', $parentId)
            ->paginate(50);
    }

    public static function deleteChildComment($productId, $idxLeft, $idxRight)
    {
        return Comment::where('comment_productId', $productId)
            ->where('comment_left', '>=', $idxLeft)
            ->where('comment_right', '<=', $idxRight)
            ->delete();
    }

    public static function updateIdxRightAfterDelete($productId, $idxRight, $width)
    {
        return Comment::where('comment_productId', $productId)
            ->where('comment_right', '>', $idxRight)
            ->decrement('comment_right', $width);
    }

    public static function updateIdxLeftAfterDelete($productId, $idxLeft, $width)
    {
        return Comment::where('comment_productId', $productId)
            ->where('comment_left', '>', $idxLeft)
            ->decrement('comment_left', $width);
    }
}
