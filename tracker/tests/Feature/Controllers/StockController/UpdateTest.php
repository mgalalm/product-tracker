<?php

use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use function Pest\Laravel\{put, seed};
use function Pest\Laravel\actingAs;
use App\Models\Retailer;
use App\Models\User;
use App\Models\Product;

it('requires authntication', function () {
    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();

    put(route('products.stocks.update', [$product, $stock]))->assertRedirect(route('login'));
});

it('can detach a stock and attach new one ', function () {
    // create a stock, product and retailer


    seed(RetailerWithProductSeeder::class);


    $stock = Stock::first();

    $retailer = Retailer::factory()->create(
        [
            'name' => 'Test Retailer',
            'domain' => 'example.com',
        ]
    );

    $product = $stock->products->first();

    mockClientRequest();

    actingAs($product->user)->put(route('products.stocks.update', [$product, $stock]), [
        'retailer' => $retailer->id,
        'url' => 'https://www.example.com/products/123',

    ])->assertRedirect(route('products.show', $product));

    // assert that the product has no longer the stock
    $this->assertDatabaseMissing('product_stock', [
        'product_id' => $product->id,
        'stock_id' => $stock->id,
    ]);

    // assert that the product has the new stock
    $this->assertDatabaseHas('stocks', [
        'retailer_id' => $retailer->id,
        'url' => $url = 'https://www.example.com/products/123',
    ]);

    // assert that the product has the new stock
    $this->assertDatabaseHas('product_stock', [
        'product_id' => $product->id,
        'stock_id' => Stock::where('url', $url)->first()->id,
    ]);

});


it('can\'t detach a stock with a different user', function () {
    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();

    actingAs(User::factory()->create())->put(route('products.stocks.update', [$product, $stock]), [
        'retailer' => $stock->retailer_id,
        'url' => $stock->url,
    ])->assertForbidden();
});

it('require a valid retailer', function ($value) {

    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();
    $user = $product->user;

    mockClientRequest();

    actingAs($user)->put(
        route('products.stocks.update', [$product, $stock]),
        [
            'url' => 'https://example.com/products/apple-airpods-pro-2nd-generation-with-magsafe-charging-case-usbc-white-10255167.html',
            'retailer' => $value,
        ],

    )->assertInvalid('retailer');

})->with([
            'null' => null,
            'empty' => '',
            'whitespace' => ' ',
            'not exists' => 100,
            'boolean' => true,
        ]);


it('require a valid stock url', function ($value) {

    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();
    $user = $product->user;

    $retailer = Retailer::factory()->create([
        'name' => 'Test',
        'domain' => 'example.com',
    ]);


    mockClientRequest(pattern: '/(?:products)\/.*-(\d+)(?=\.html)/');


    actingAs($user)->put( route('products.stocks.update', [$product, $stock]),  [
        'name' => 'This is a product',
        'stocks' => [
            [
                'url' => $value,
                'retailer' => $retailer->id,
            ]
        ],
    ])->assertInvalid('url');

})->with([
    'null' => null,
    'empty' => '',
    'whitespace' => ' ',
    'not url' => 'not-url',
    'not valid url' => 'https://example1.com',
]);

// it('require a valid stock url', function ($value) {

//     seed(RetailerWithProductSeeder::class);

//     $stock = Stock::first();
//     $product = $stock->products->first();
//     $user = $product->user;


//     mockClientRequest(pattern: '/(?:products)\/.*-(\d+)(?=\.html)/');

//     actingAs($user)->put(route('products.stocks.update', [$product, $stock]), [
//         'retailer' => $retailer->id,
//         'url' => $value,
//     ])->assertInvalid('url');

// })->with([
//     'null' => null,
//     'empty' => '',
//     'whitespace' => ' ',
//     'not url' => 'not-url',
//     'not valid url' => 'https://example1.com',
// ]);


