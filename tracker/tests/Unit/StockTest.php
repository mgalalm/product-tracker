<?php

use App\Models\{Retailer, Product, Stock};
use App\Exceptions\ClientException;
use Illuminate\Support\Facades\Queue;

// it should throw an exception if the client is not found
it('throws an exception if the client is not found', function () {

    $this->expectException(ClientException::class);

    Queue::fake();
    $retailer = new Retailer(['name' => 'Foo Retailer', 'url' => 'http://foo.com']);
    $product = new Product(['name' => 'Foo Product']);
    $stock = new Stock(['price' => 1000, 'url' => 'http://foo.com', 'in_stock' => true, 'sku' => '12345']);

    $stock->retailer = $retailer;
    $stock->product = $product;

    $stock->track();

});


// it should give the right status

it('gives the right status', function ($inStock, $minutes, $expectedStatus) {
    $this->freezeTime();

    $stock = Stock::factory()->make([
        'in_stock' => $inStock,
        'created_at' => now(),
    ]);

    $this->travel($minutes)->minutes();

    expect($stock->status)->toBe($expectedStatus);
})->with([
    [true, 60, 'In Stock'],
    [false, 60, 'Out of Stock'],
    [null, 4, 'Not Tracked Yet'],
    [null, 6, 'Processing'],
]);
