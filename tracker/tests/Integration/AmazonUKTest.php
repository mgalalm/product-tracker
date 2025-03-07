<?php

use App\Clients\AmazonUK;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Stock;
use App\Clients\StockStatus;
use Illuminate\Support\Facades\Http;

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



it("handles different availability string status", function($value, $expected) {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update([
       'sku' => 'B0C7XNR559',
       'url' => 'https://www.amazon.co.uk/echo-show-15/dp/B0C7XNR559',
    ]);

    Http::fake([
        '*/amazon-uk/B0C7XNR559' => Http::response([
            'availability' => $value ,
            'price' => '£199.99'
        ], 200)
    ]);

    try {
        $stockStatus = (new AmazonUK())->checkAvailability($stock);
        $this->assertInstanceOf(StockStatus::class, $stockStatus);
        $this->assertEquals($stockStatus->available, $expected);
        $this->assertEquals('19999', $stockStatus->price->getAmount());

    } catch (Exception $e) {
        $this->fail("Failed to track the Amazon UK stock: {$e->getMessage()}");
    }
})->with([
    ['In stock', true],
    ['Out of stock', false],
    ['Only 14 left in stock', true]
]);
