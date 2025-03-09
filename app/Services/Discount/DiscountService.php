<?php

namespace App\Services\Discount;

use App\Enums\Discount;
use App\Enums\HttpStatusCodes;
use App\Models\Repositories\DiscountRepository;
use App\Services\Contracts\DiscountServiceInterface;
use Illuminate\Support\Facades\Http;
use PDO;

class DiscountService implements DiscountServiceInterface
{
    static function createNewDiscount($data)
    {
        $foundDiscount = DiscountRepository::checkExistDiscount([
            'discount_code' => $data['discount_code'],
            'discount_shop' => $data['discount_shop'],
        ]);

        if ($foundDiscount) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Discount already exists!'
            ];
        }

        // validation date time
        if (date('Y-m-d H:i:s') > $data['discount_start_date'] || date('Y-m-d H:i:s') > $data['discount_end_date'] ||
            date('Y-m-d H:i:s') === $data['discount_start_date'] || date('Y-m-d H:i:s') === $data['discount_end_date']) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Invalid time!'
            ];
        }

        $dataUpdate = $data['discount_applies_to'] === Discount::ALL ? $data['discount_product_ids'] = [] : $data['discount_product_ids'];

        if ($data['discount_applies_to'] === Discount::SPECIFIC && empty($data['discount_product_ids'])) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Product id is not empty when discount apply is specific!'
            ];
        }

        return DiscountRepository::createNewDiscount(array_merge($dataUpdate, $data));
    }

    static function updateDiscountByID($discount_id, $discount_shop, $data)
    {
        if (! isset($data['discount_code'])) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Discount code not found!"
            ];
        }

        $foundDiscount = DiscountRepository::checkExistDiscount([
            'id' => $discount_id,
            'discount_code' => $data['discount_code'],
        ]);

        if (! $foundDiscount) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Discount doesn't exists!"
            ];
        }

        // validation date time
        if (date('Y-m-d H:i:s') > $data['discount_start_date'] || date('Y-m-d H:i:s') > $data['discount_end_date'] ||
            date('Y-m-d H:i:s') === $data['discount_start_date'] || date('Y-m-d H:i:s') === $data['discount_end_date']) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Invalid time!'
            ];
        }

        if ($foundDiscount->discount_uses_count > $foundDiscount->discount_max_uses) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'You cannot update with this value!'
            ];
        }

        if ($foundDiscount->discount_uses_count > 0 && empty($foundDiscount->discount_users_used)) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'User ID is required!'
            ];
        }

        return DiscountRepository::updateDiscountByID($foundDiscount, $data);
    }

    static function discountDetails($discount_id)
    {
        $discount = DiscountRepository::checkExistDiscount([
            'id' => $discount_id,
            'discount_is_active' => true
        ]);

        if (! $discount) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Discount doesn't exists!"
            ];
        }

        return $discount;
    }

    static function getListDiscountWithProduct($url)
    {
        $query = explode("?", $url, 2)[1] ?? '';
        parse_str($query, $params);

        $params = array_merge($params, ['discount_is_active' => true]);

        $discounts = DiscountRepository::getListDiscountCodes($params);

        return $discounts;
    }

    static function getListDiscountByShop($discount_shop)
    {
        $discounts = DiscountRepository::getListDiscountCodes([
            'discount_shop' => $discount_shop,
        ]);

        return $discounts;
    }

    static function userApplyDiscount($data)
    {

        foreach ($data['products'] as $product) {
            if (empty($product['product_id'])) {
                return [
                    'statusCode' => HttpStatusCodes::BAD_REQUEST,
                    'message' => 'Product ID is required for all products'
                ];
            }
        }

        $discount = DiscountRepository::checkExistDiscount([
            'discount_code' => $data['discount_code'],
            'discount_shop' => $data['discount_shop'],
            'discount_is_active' => true
        ]);

        if (! $discount) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Discount doesn\'t exists!'
            ];
        }

        // validate discount expired
        if (date('Y-m-d H:i:s') < $discount->discount_start_date && date('Y-m-d H:i:s') > $discount->discount_end_date) {
            return [
                'statusCode' => HttpStatusCodes::BAD_REQUEST,
                'message' => 'Discount already expired!'
            ];
        }

        // check max uses
        if (! $discount->discount_max_uses) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => 'Discount are out!'
            ];
        }

        // check minimum orders value
        $totalOrders = 0;
        if ($discount->discount_min_orders_value > 0) {
            $products = $data['products'];
            foreach ($products as $key => $val) {
                $totalOrders += $val['price'] * $val['quantity'];
            }

            if ($totalOrders < $discount->discount_min_orders_value) {
                return [
                    'statusCode' => HttpStatusCodes::BAD_REQUEST,
                    'message' => "This discount requires a minimum order of $discount->discount_min_orders_value"
                ];
            }

            // if user apply discount
            if ($discount->discount_max_uses_per_user > 0) {
                // ensure discount_user_used is array
                $discountUsersUsed = $discount->discount_users_used ?? [];
                $countUserUsed = array_count_values($discountUsersUsed);

                // count current uses inside discount_users_used
                $currentUses = $countUserUsed[$data['user_id']] ?? 0;

                if ($currentUses >= $discount->discount_max_uses_per_user) {
                    return [
                        'statusCode' => HttpStatusCodes::BAD_REQUEST,
                        'message' => "You have used more than the allowed discount code!"
                    ];
                }

                $discountUsersUsed[] = $data['user_id'];
                $discount->discount_users_used = $discountUsersUsed;
                $discount->discount_uses_count += 1;

                if ($discount->discount_uses_count > $discount->discount_max_uses) {
                    return [
                        'statusCode' => HttpStatusCodes::BAD_REQUEST,
                        'message' => "Discount codes are out!"
                    ];
                }

                $discount->update();
            }
        }

        $amout = $discount->discount_type === Discount::FIXED_AMOUNT ? $discount->discount_value : $totalOrders * ($discount->discount_value / 100);

        return [
            'price' => $totalOrders,
            'discount codes' => $amout,
            'total amount' => $totalOrders - $amout
        ];
    }

    static function userCanceledDiscount($data)
    {
        $discount = DiscountRepository::checkExistDiscount([
            'discount_code' => $data['discount_code'],
            'discount_shop' => $data['discount_shop'],
            'discount_is_active' => true
        ]);

        if (! $discount) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Discount doesn't exists!"
            ];
        }

        // remove user_id inside discount_users_used
        $totalOrders = 0;
        $discountUsersUsed = $discount->discount_users_used ?? [];
        $index = array_search($data['user_id'], $discountUsersUsed);


        if (empty($discountUsersUsed[$index]) || $data['user_id'] !== $discountUsersUsed[$index]) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "This user is not found within the list of discount users used"
            ];
        }

        if ($index !== false)
            unset($discountUsersUsed[$index]);

        $discount->discount_users_used = array_values($discountUsersUsed);
        $discount->discount_uses_count = max(0, $discount->discount_uses_count - 1);

        $discount->update();
        return $discount->refresh();
    }

    static function deleteDiscountByID($discount_id, $discount_shop)
    {
        $foundDiscount = DiscountRepository::checkExistDiscount([
            'id' => $discount_id,
            'discount_shop' => $discount_shop,
            'discount_is_active' => true
        ]);

        if (! $foundDiscount) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Discount doesn't exists!"
            ];
        }

        return $foundDiscount->delete();
    }
}
