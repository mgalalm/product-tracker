<?php

use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\User;

use function Pest\Laravel\actingAs;


it('can show a product', function () {
    $product = Product::factory()->create();
    actingAs($product->user)->
    get($product->showRoute())
        ->assertComponent('Products/Show');
});


it('passes the product to the view', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $product = Product::first();

    // load the stocks relationship
    $product->load('stocks');

    actingAs($product->user)->get($product->showRoute())
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

    actingAs($product->user)->get($product->showRoute())
        ->assertHasPaginatedResource('stocks', StockResource::collection($stocks->reverse()));
});



it('can\'t show a product with a different user', function () {
    $product = Product::factory()->create();
    actingAs(User::factory()->create())->
    get($product->showRoute())
        ->assertForbidden();
});


// it will redirect if the slug is incorrect
it('will redirect if the slug is incorrect', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);
    actingAs($product->user)->
    get(route('products.show', [$product, 'wrong-slug']))
        ->assertRedirect($product->showRoute());
});
