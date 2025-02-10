<?php

use App\Clients\AmazonUK;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;

it("tracks products", function() {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
       'sku' => 'B0C7XNR559',
       'url' => 'https://www.amazon.co.uk/echo-show-15/dp/B0C7XNR559',
    ]);

    try {
        $stockStatus = (new AmazonUK())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertInstanceOf(StockStatus::class, $stockStatus);

    } catch (Exception $e) {
        $this->fail("Failed to track the Amazon UK stock: {$e->getMessage()}");
    }
});
