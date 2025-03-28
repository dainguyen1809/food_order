<?php

namespace App\Services\Inventory;

use App\Enums\HttpStatusCodes;
use App\Models\Repositories\InventoryRepository;
use App\Models\Repositories\ProductRepository;
use App\Services\Contracts\InventoryServiceInterface;

class InventoryService implements InventoryServiceInterface
{
    public static function addStockToInventory($data)
    {
        $productID = $data['productID'];
        $shopID = $data['shopID'];
        $stock = $data['stock'];
        $location = $data['location'];

        $product = ProductRepository::getProductByID($productID);
        if (! $product) {
            return [
                'statusCode' => HttpStatusCodes::NOT_FOUND,
                'message' => "Product doesn't exists!"
            ];
        }

        return InventoryRepository::addStockToInventory($productID, $shopID, $stock, $location);
    }
}
