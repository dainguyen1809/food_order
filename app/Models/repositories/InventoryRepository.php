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

    public function reservationInventory($productID, $quantity, $cartID)
    {

        $inventory = Inventory::where('inven_productID', $productID)
            ->where('inven_stock', '>=', $quantity)
            ->first();

        if (! $inventory)
            return null;

        dd($inventory->inven_stock);

        $inventory->update([
            'inven_stock' => $inventory->inven_stock - $quantity
        ]);
        $inventory->inven_reservation[] = [
            'quantity' => $quantity,
            'cart_id' => $cartID,
            'created_at' => now(),
        ];
        $inventory->save();

        return $inventory;
    }

    public static function addStockToInventory($shopID, $productID, $stock, $location)
    {
        $inventory = Inventory::updateOrCreate(
            [
                'inven_shopID' => $shopID,
                'inven_productID' => $productID,
                'inven_location' => $location,
            ]
        );
        $inventory->increment('inven_stock', $stock);

        return $inventory->refresh();
    }

}
