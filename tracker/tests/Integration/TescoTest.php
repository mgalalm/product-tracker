<?php

use App\Clients\Tesco;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;

it("tracks products", function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
        'sku' => '312095473',
        'url' => 'https://www.tesco.ie/groceries/en-IE/products/312095473',
    ]);

    try {
        $stockStatus = (new Tesco())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertInstanceOf(StockStatus::class, $stockStatus);


        // currency is in euro despite the amount value
        $this->assertEquals('EUR', $stockStatus->price->getCurrency());

        // price amount is positive
        $this->assertGreaterThan(0, $stockStatus->price->getAmount());

        $this->assertEquals($stockStatus->available, true);


    } catch (Exception $e) {
        $this->fail("Failed to track the Tesco stock: {$e->getMessage()}");
    }
});
