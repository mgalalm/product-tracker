<?php

use App\Clients\Dunnes;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;


it("tracks products", function() {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
       'sku' => '100274908',
       'url' => "https://www.dunnesstoresgrocery.com/sm/delivery/rsid/428/product/l'or-classique-instant-coffee-150g-id-100274908",
    ]);

    try {
        $stockStatus = (new Dunnes())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertInstanceOf(StockStatus::class, $stockStatus);

    } catch (Exception $e) {
        $this->fail("Failed to track the Dunnes  stock: {$e->getMessage()}");
    }
});
