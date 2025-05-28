
<?php

use App\Clients\Supervalu;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;


it("tracks products", function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
        'sku' => '1832340000',
        'url' => "https://shop.supervalu.ie/sm/delivery/rsid/5551/product/1832340000",
    ]);

    try {
        $stockStatus = (new Supervalu())->checkAvailability($stock);
        // assert $stockStatus is an instance of StockStatus class
        $this->assertInstanceOf(StockStatus::class, $stockStatus);

        // price amount is positive
        $this->assertGreaterThan(0, $stockStatus->price->getAmount());


        $this->assertEquals('EUR', $stockStatus->price->getCurrency());

    } catch (Exception $e) {
        $this->fail("Failed to track the Supervalu  stock: {$e->getMessage()}");
    }
});
