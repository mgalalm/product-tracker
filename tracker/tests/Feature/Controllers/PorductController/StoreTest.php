<?php

use App\Models\Retailer;
use App\Models\User;
use App\Models\Product;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('products.store'))->assertRedirect(route('login'));
});

it('can store a product', function () {
    Notification::fake();
    $user = User::factory()->create();
    mockClientRequest(pattern: '/(?:products)\/.*-(\d+)(?=\.html)/');

    // // create a retailer for this client class
    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);

    actingAs($user)->post(route('products.store'), [
        'name' => 'This is a product',
        'stocks' => [
            [
                'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
                'retailer' => $retailer->id,
            ],
            [
                'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-20000.html',
                'retailer' => $retailer->id,
            ]

        ],

    ])->assertStatus(302)
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas(Product::class, [
        'name' => 'This is a product',
    ]);

    $this->assertDatabaseHas('stocks', [
        'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
        'retailer_id' => $retailer->id,
        'sku' => 10255167,
    ]);

    $this->assertDatabaseHas('stocks', [
        'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-20000.html',
        'retailer_id' => $retailer->id,
        'sku' => 20000,
    ]);

    // no notification should be sent as the stock is added for the first time
    Notification::assertNothingSent();

});


// it avoid storing the same stock twice

// it()

it('redirects to the product page after storing', function () {
    $user = User::factory()->create();

    mockClientRequest();

    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);


    actingAs($user)->post(route('products.store'), [
        'name' => 'This is a product',
        'stocks' => [
            [
                'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
                'retailer' => $retailer->id,
            ]
        ]

    ])->assertRedirect(route('products.index'));

});

it('require a valid product name', function ($value) {
    $user = User::factory()->create();

    actingAs($user)->post(route('products.store'), [
        'name' => $value,
    ])->assertInvalid('name');

})->with([
            'null' => null,
            'empty' => '',
            'whitespace' => ' ',
            'too long' => str_repeat('a', 256),
            'boolean' => true,
        ]);


it('require a valid retailer', function ($value) {
    $user = User::factory()->create();

    mockClientRequest();

    $response = actingAs($user)->post(route('products.store'), [

            'name' => 'This is a product',
            'stocks' => [
                [
                    'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
                    'retailer' => $value,
                ]
            ],

        ]);
        // dd($response);
    $response->assertInvalid('stocks.0.retailer');
    // $response->assertSessionHasErrors('stocks.0.retailer');

})->with([
            'null' => null,
            'empty' => '',
            'whitespace' => ' ',
            'not exists' => 100,
            'boolean' => true,
        ]);

it('require a valid stock url', function ($value) {
    $user = User::factory()->create();

    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);

    mockClientRequest(pattern: '/(?:products)\/.*-(\d+)(?=\.html)/');


    actingAs($user)->post(route('products.store'),  [
        'name' => 'This is a product',
        'stocks' => [
            [
                'url' => $value,
                'retailer' => $retailer->id,
            ]
        ],
    ])->assertInvalid('stocks.0.url');

})->with([
    'null' => null,
    'empty' => '',
    'whitespace' => ' ',
    'not url' => 'not-url',
    'not valid url' => 'https://example1.com',
]);
