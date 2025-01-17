<?php

use App\Models\User;
use App\Models\Product;
use function Pest\Laravel\actingAs;

it('can store a product', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('products.store'),  [
        'name' => 'This is a product',
    ]);

    $this->assertDatabaseHas(Product::class, [
        'name' => 'This is a product',
    ]);

});

it('redirects to the product page after storing', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('products.store'),  [
        'name' => 'This is a product',
    ])->assertRedirect(route('products.index'));

});


it('require a valid product name', function ($value) {
    $user = User::factory()->create();

    actingAs($user)->post(route('products.store'),  [
        'name' => $value,
    ])->assertInvalid('name');

})->with([
    'null' => null,
    'empty' => '',
    'whitespace' => ' ',
    'too long' => str_repeat('a', 256),
    'boolean' => true,
]);

