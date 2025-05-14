<?php

use App\Models\Product;
use App\Models\Retailer;

// it gets product price range
it('gets product price range', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
    ]);

    $product->stocks()->createMany(
        [
            [
                'retailer_id' => Retailer::factory()->create(['name' => fake()->name, 'domain' => fake()->domainName()])->id,// 'retailer_id' => Retailer::factory()->create(['name' => 'Amazon UK', 'domain' => 'amazon.co.uk'])->id,
                'price' => 2000,
                'url' => fake()->url,
            ],
            [
                'retailer_id' => Retailer::factory()->create(['name' => fake()->name, 'domain' => fake()->domainName()])->id,                // 'retailer_id' => Retailer::factory()->create(['name' => 'Currys', 'domain' => 'currys.ie'])->id,
                'price' => 100,
                'url' => fake()->url,
            ]
        ]
    );

    $this->assertEquals('€1.00 - €20.00', $product->price_range);
});

// it gets product price range with one stock
it('gets product price range with one stock', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
    ]);

    $product->stocks()->createMany(
        [
            [
                'retailer_id' => Retailer::factory()->create(['name' => fake()->name, 'domain' => fake()->domainName()])->id,
                // 'retailer_id' => Retailer::factory()->create(['name' => 'Amazon UK', 'domain' => 'amazon.co.uk'])->id,
                'price' => 2000,
                'url' => fake()->url,
            ]
        ]
    );

    $this->assertEquals('€20.00', $product->price_range);
});

// it can generates a route to the show page
it('can generates a route to the show page', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
    ]);

    expect($product->showRoute())->toBe(route('products.show', [$product, Str::slug($product->name)]));
});

// it can generates a route to the show page with query string
it('can generates a route to the show page with query string', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
    ]);

    expect($product->showRoute(['foo' => 'bar']))->toBe(route('products.show', [$product, Str::slug($product->name), 'foo' => 'bar']));
});


