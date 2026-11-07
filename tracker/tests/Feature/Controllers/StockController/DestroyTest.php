
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

    actingAs($product->user)->delete(route('products.stocks.destroy', [$product, $stock])
        )->assertRedirect($product->showRoute());

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
    actingAs(User::factory()->create())->delete(route('products.stocks.destroy', [$product, $stock])
        )->assertForbidden();

});

// it detach the stock from a given product and hold on the relationship for other products
it('detach the stock from a given product and hold on the relationship for other products', function () {
    seed(RetailerWithProductSeeder::class);

    $stock = Stock::first();
    $product = $stock->products->first();

    $anotherProduct = $product->replicate();
    $anotherProduct->save();
    $anotherProduct->refresh();

    actingAs($product->user)->delete(route('products.stocks.destroy', [$anotherProduct, $stock])
        )->assertRedirect($anotherProduct->showRoute());

    // the stock detached from the product
    $this->assertDatabaseMissing('product_stock', [
        'product_id' => $anotherProduct->id,
        'stock_id' => $stock->id
    ]);

    // the stock still associated with the other product
    $this->assertDatabaseHas('product_stock', [
        'product_id' => $product->id,
        'stock_id' => $stock->id
    ]);
});

