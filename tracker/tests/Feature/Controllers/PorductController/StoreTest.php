<?php

use App\Models\Retailer;
use App\Models\User;
use App\Models\Product;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Notification;


it('can store a product', function () {
    Notification::fake();
    $user = User::factory()->create();
    mockClientRequest(pattern: '/(?:products)\/.*-(\d+)(?=\.html)/');

    // // create a retailer for this client class
    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);

    actingAs($user)->post(route('products.store'),  [
        'name' => 'This is a product',
        'stockUrl' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
        'retailer' => $retailer->id,

    ])->assertStatus(302)
    ->assertSessionHasNoErrors();
    $this->assertDatabaseHas(Product::class, [
        'name' => 'This is a product',
    ]);

    // no notification should be sent as the stock is added for the first time
    Notification::assertNothingSent();

});

it('redirects to the product page after storing', function () {
    $user = User::factory()->create();

    mockClientRequest();

    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);


    actingAs($user)->post(route('products.store'),  [
        'name' => 'This is a product',
        'stockUrl' => 'https://example.com/',
        'retailer' => $retailer->id,
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

