<?php

namespace App\Services\Contracts;

interface DiscountServiceInterface
{
    static function createNewDiscount($data);
    static function updateDiscountByID($discount_id, $discount_shop, $data);
    static function discountDetails($discount_id);
    static function getListDiscountWithProduct($url);
    static function getListDiscountByShop($discount_shop);
    static function userApplyDiscount($data);
    static function userCanceledDiscount($data);
    static function deleteDiscountByID($discount_id, $discount_shop);
}
