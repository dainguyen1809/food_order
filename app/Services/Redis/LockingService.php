<?php

namespace App\Services\Redis;

use App\Models\Repositories\InventoryRepository;
use Illuminate\Support\Facades\Redis;

class LockingService
{
    protected $redis;

    public function __construct($connection = 'food_order')
    {
        $this->redis = Redis::connection('food_order');
    }

    // try the lock
    public function acquireLock($productID, $quantity, $cartID)
    {
        $key = "lock_".date("Y")."_{$productID}";
        $retries = 10;
        $expireTime = 3000;
        $delay = 50;
        $inventory = new InventoryRepository();

        for ($i = 0; $i < $retries; $i++) {
            $result = $this->redis->setnx($key, now()->timestamp);

            \Log::channel('debug_logs')->debug("Result ===> ".$result);
            if ($result === 1) {
                try {
                    $isReservation = $inventory->reservationInventory($productID, $quantity, $cartID);

                    $this->redis->pexpire($key, $expireTime);
                    return $key;
                } catch (\Exception $e) {
                    // free up lock
                    $this->redis->del($key);
                    \Log::error("Reservation Failed: {$e->getMessage()}");
                    return null;
                }
            } else {
                usleep($delay * 10000);
            }
        }

        \Log::channel('warning_logs')->warning("Failed to acquire lock for {$key} after {$retries} attempts.");
        return null;
    }

    // Free up lock
    public function freeUpLock($keyLock)
    {
        return $this->redis->del($keyLock);
    }
}
