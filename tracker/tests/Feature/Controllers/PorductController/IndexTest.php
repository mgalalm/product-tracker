<?php

use App\Http\Resources\ProductResource;
use function Pest\Laravel\get;
use Database\Seeders\RetailerWithProductSeeder;

use App\Models\Product;

it('should return the correct component', function () {
    $this->get(route('products.index'))
        ->assertComponent('Products/Index');
});

it('passes the products to the view', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $products = Product::all();

    get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection($products->reverse()));
});



