<?php

namespace App\Models\Repositories;

use App\Models\Inventory;

class InventoryRepository
{
    public static function insertInventory($productID, $shopID, $stock, $location = 'unknown')
    {
        return Inventory::create([
            'inven_shopID' => $shopID,
            'inven_productID' => $productID,
            'inven_location' => $location,
            'inven_stock' => $stock,
        ]);
    }
}
