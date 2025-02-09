<?php

use Database\Seeders\RetailerWithProductSeeder;
use function Pest\Laravel\delete;
use function Pest\Laravel\seed;
use App\Models\Product;
use function Pest\Laravel\actingAs;
use App\Models\User;


it('requires authentication', function () {
    seed(RetailerWithProductSeeder::class);
    $product = Product::first();
    delete(route('products.destroy', $product))->assertRedirect(route('login'));
});

it('can delete a product', function () {
    seed(RetailerWithProductSeeder::class);
    $product = Product::first();
    actingAs($product->user)->delete(route('products.destroy', $product)
        )->assertRedirect(route('products.index'));
    $this->assertModelMissing($product);

});

it('can\'t delete a product with a different user', function () {
    seed(RetailerWithProductSeeder::class);

    $product = Product::first();
    actingAs(User::factory()->create())->delete(route('products.destroy', $product)
        )->assertForbidden();

});
