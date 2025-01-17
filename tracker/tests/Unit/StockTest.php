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
