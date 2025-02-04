<?php

use App\Clients\Currys;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;

it("tracks products", function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
        'sku' => '10248709',
        'url' => 'https://www.currys.ie/products/logik-lsd55w23-5050-fridge-freezer-white-10248709.html',
    ]);

    try {
        $stockStatus = (new Currys())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertInstanceOf(StockStatus::class, $stockStatus);

        // currency is in euro despite the amount value
        $this->assertEquals('EUR', $stockStatus->price->getCurrency());

        // price amount is positive
        $this->assertGreaterThan(0, $stockStatus->price->getAmount());

        $this->assertEquals($stockStatus->available, true);


    } catch (Exception $e) {
        $this->fail("Failed to track the Currys stock: {$e->getMessage()}");
    }
});

it("tracks unavailable product", function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
        'sku' => '10246803',
        'url' => 'https://www.currys.ie/products/logik-lsd55w23-5050-fridge-freezer-white-10246803.html',
    ]);

    try {
        $stockStatus = (new Currys())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertEquals($stockStatus->available, false);


    } catch (Exception $e) {
        $this->fail("Failed to track the Currys stock: {$e->getMessage()}");
    }
});
