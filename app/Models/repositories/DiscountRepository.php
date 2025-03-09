<?php

namespace App\Models\Repositories;

use App\Models\Discount;

class DiscountRepository
{
    public static function checkExistDiscount(array $conditions = [])
    {
        $query = Discount::query();

        foreach ($conditions as $column => $value) {
            if (! is_null($value)) {
                $query->where($column, $value);
            }
        }

        return $query->first();
    }

    public static function createNewDiscount($data)
    {
        return Discount::create($data);
    }

    public static function updateDiscountByID($discount, $data)
    {
        $updated = $discount->update($data);

        return $discount->refresh();
    }

    public static function getListDiscountCodes(array $conditions = [])
    {
        $discounts = Discount::query();

        foreach ($conditions as $column => $value) {
            if (! is_null($value)) {
                $discounts->where($column, $value);
                // ->where('discount_is_active', true);
            }
        }

        return $discounts->paginate(50);
    }

    // public static function deleteDiscountByID()
    // {
    //     return Discount::delete();
    // }
}
