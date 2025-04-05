<?php

namespace App\Services\Comments;

use App\Enums\HttpStatusCodes;
use App\Models\Comment;
use App\Models\Repositories\CommentRepository;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\CommentServiceInterface;

class CommentService implements CommentServiceInterface
{

    /*
                                                                    (L: 1) 1 R(28)

                            (L:2) 1.1 (R:11)                                                          (L:12) 1.2 (R:27)

              (L:3) 1.1.1 (R:8)             (L:9) 1.1.2 (R:10)                    (L:13) 1.2.1 (R:22)             (L:23) 1.2.2 (R:24)       (L:25) 1.2.3 (R:26)

      (L:4) 1.1.1.1 (R:5)   (L:6) 1.1.1.2 (R:7)                   (L:14) 1.2.1.1 (R:19)         (L:20) 1.2.1.2 (R:21)

                                                    (L:15) 1.2.1.1.1  (R:16)  (L:17) 1.2.1.1.2 (R:18)
     */

    public static function createComment($data)
    {
        $productId = $data['productId'];
        $parentId = $data['parentId'];

        $idxRight = 0;
        if ($parentId) {
            // parent comment
            $parent = CommentRepository::findParentComment($parentId);
            if (! $parent) {
                return [
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => 'Comment not found'
                ];
            }

            $idxRight = $parent->comment_right;
            CommentRepository::updateIdxComment($productId, $idxRight);
            return CommentRepository::createComment($data, $idxRight);
        }

        $maxIdxRight = CommentRepository::findMaxIdxRight($productId);
        $idxRight = $maxIdxRight ? $maxIdxRight->comment_right + 1 : 1;

        return CommentRepository::createComment($data, $idxRight);
    }

    public static function getListComments($productId, $parentId)
    {
        if ($parentId) {
            $parent = CommentRepository::findParentComment($parentId);
            if (! $parent) {
                return [
                    'statusCode' => HttpStatusCodes::NOT_FOUND,
                    'message' => 'Comment not found'
                ];
            }

            $comments = CommentRepository::getListComments($productId, $parent->comment_left, $parent->comment_right);

            return $comments;
        }

        $comments = CommentRepository::getParentComment($productId, $parentId);

        return $comments;
    }

    public static function deleteComment($productId, $commentId)
    {
        // ensure correctly product
        $foundProduct = ProductRepository::getProductByID($productId);
        if (! $foundProduct) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Product not found'
            ];
        }

        $comment = CommentRepository::getCommentById($commentId);
        if (! $comment) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Comment not found'
            ];
        }

        $idxLeft = $comment->comment_left;
        $idxRight = $comment->comment_right;

        $width = $idxRight - $idxLeft + 1;

        $delComment = CommentRepository::deleteChildComment($productId, $idxLeft, $idxRight);

        // update idx right
        $right = CommentRepository::updateIdxRightAfterDelete($productId, $idxRight, $width);

        $left = CommentRepository::updateIdxRightAfterDelete($productId, $idxLeft, $width);

        return true;
    }
}

