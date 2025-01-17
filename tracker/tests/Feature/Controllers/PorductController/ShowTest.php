<?php

use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;

it('can show a product', function () {
    $product = Product::factory()->create();

    $this->get(route('products.show', $product))
        ->assertComponent('Products/Show');
});


it('passes the product to the view', function () {


    $this->seed(RetailerWithProductSeeder::class);
    $product = Product::first();

    // load the stocks relationship
    $product->load('stocks');

    $this->get(route('products.show', $product))
        ->assertHasResource('product', ProductResource::make($product));
});


it('passes stocks to the view', function () {

    $this->withoutExceptionHandling();
    $this->seed(RetailerWithProductSeeder::class);
    $product = Product::first();

    // load the stocks relationship
    $product->load('stocks');

    $stocks = $product->stocks;

    $stocks->load('retailer');

    $this->get(route('products.show', $product))
        ->assertHasPaginatedResource('stocks', StockResource::collection($stocks->reverse()));
});
