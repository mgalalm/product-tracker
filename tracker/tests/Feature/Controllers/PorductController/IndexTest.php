<?php

use App\Http\Resources\ProductResource;
use function Pest\Laravel\get;
use Database\Seeders\RetailerWithProductSeeder;

use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;

// non authenticated user can't access the products index
it('non authenticated user can\'t access the products index', function () {
    get(route('products.index'))
        ->assertRedirect(route('login'));
});

it('should return the correct component', function () {
    $user = User::factory()->create();
    actingAs($user)->get(route('products.index'))
        ->assertComponent('Products/Index');
});

it('passes the products to the view', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $products = Product::all();
    $products->load(['type']);
    actingAs($products->first()->user)->get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection($products->reverse()));
});

// new user can't access the products index if he has no products
it('new user can\'t access the products index if he has no products', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $user = User::factory()->create();
    actingAs($user)->get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection([]));;
});


