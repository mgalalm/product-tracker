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

// it delete the stock associated with the product
it('delete the stock associated with the product', function () {
    seed(RetailerWithProductSeeder::class);
    $product = Product::first();
    $stock = $product->stocks->first();
    actingAs($product->user)->delete(route('products.destroy', $product)
        )->assertRedirect(route('products.index'));
    $this->assertModelMissing($product);
    $this->assertModelMissing($stock);
});

// it doesn't delete the stock associated with the product if it's associated with another product
it('doesn\'t delete the stock associated with the product if it\'s associated with another product', function () {
    seed(RetailerWithProductSeeder::class);
    $product = Product::first();
    $stock = $product->stocks->first();
    $anotherProduct = Product::factory()->create();
    $anotherProduct->stocks()->attach($stock);
    actingAs($product->user)->delete(route('products.destroy', $product)
        )->assertRedirect(route('products.index'));
    $this->assertModelMissing($product);
    $this->assertModelExists($stock);
});
