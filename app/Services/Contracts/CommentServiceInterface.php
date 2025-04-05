<?php

namespace App\Services\Contracts;

interface CommentServiceInterface
{
    public static function createComment($data);
    public static function getListComments($productId, $parentId);
    public static function deleteComment($productId, $commentId);
}
