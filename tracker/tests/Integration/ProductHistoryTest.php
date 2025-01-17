<?php

use App\Models\{Product, History};
use Database\Seeders\RetailerWithProductSeeder;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use Cknow\Money\Money;

it('should record history every time stock is tracked', function() {
    $this->seed(RetailerWithProductSeeder::class);

    $product = Product::first();

    $this->assertCount(0, $product->history);

    mockClientRequest($available = true, price: '£20.0');

    $product->track();

    $this->assertCount(1, $product->refresh()->history);

    $history = $product->history->first();

    $stock = $product->stocks()->first();
    $this->assertEquals(2000, $history->price);
    $this->assertEquals($available, $history->in_stock);
    $this->assertEquals($product->id, $history->product_id);
    $this->assertEquals($stock->id, $history->stock_id);
});
