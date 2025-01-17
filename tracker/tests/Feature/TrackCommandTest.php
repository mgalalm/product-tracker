<?php

use App\Notifications\ImportantStockUpdate;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it("tracks products stock", function () {
    $this->seed(RetailerWithProductSeeder::class);

    $product = Product::first();

    $this->assertFalse($product->inStock());

    mockClientRequest(available: true, price: '£20.0');

    $this->artisan('track');

    $this->assertTrue(Product::first()->inStock());
});

it('notify the user when the stock is now available', function () {

    Notification::fake();
    $this->seed(RetailerWithProductSeeder::class);

    mockClientRequest(available: true, price: '£20.0');

    $this->artisan('track');

    Notification::assertSentTo(User::first(), ImportantStockUpdate::class);

});


it('doesn\'t notify the user when the stock remains unavailable', function () {

    Notification::fake();

    $this->seed(RetailerWithProductSeeder::class);

    mockClientRequest(available: false, price: '£20.0');

    $this->artisan('track');

    Notification::assertNothingSent();

});

