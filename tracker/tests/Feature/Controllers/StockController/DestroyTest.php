
<?php

use Database\Seeders\RetailerWithProductSeeder;
use function Pest\Laravel\seed;
use function Pest\Laravel\actingAs;
use App\Models\Stock;
use App\Models\User;


it('can detach a stock', function () {
    seed(RetailerWithProductSeeder::class);
    $stock = Stock::first();
    $product = $stock->products->first();

    actingAs($product->user)->delete(route('stocks.destroy', [$product, $stock])
        )->assertRedirect(route('products.show', $product));

    // the stock detached from the product
    $this->assertDatabaseMissing('product_stock', [
        'product_id' => $product->id,
        'stock_id' => $stock->id
    ]);
});

it('can\'t detach a stock with a different user', function () {
    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();
    actingAs(User::factory()->create())->delete(route('stocks.destroy', [$product, $stock])
        )->assertForbidden();

});

