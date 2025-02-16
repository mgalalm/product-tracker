<?php

use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use function Pest\Laravel\ {put, seed};

it('requires authntication', function () {
    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();

    put(route('stocks.update', [$product, $stock]))->assertRedirect(route('login'));
});
