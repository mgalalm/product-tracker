<?php

use App\Notifications\ImportantStockUpdate;
use Database\Seeders\RetailerWithProductSeeder;
use App\Models\Product;
use App\Models\User;
use App\Events\PriceDropped;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

// use Illuminate\Support\Sleep;

// it("tracks products stock", function () {
//     // Sleep::fake();
//     $this->seed(RetailerWithProductSeeder::class);

//     $product = Product::first();

//     $this->assertFalse($product->inStock());

//     mockClientRequest(available: true, price: '£20.0');


//     $this->artisan('track');



//     $this->assertTrue(Product::first()->inStock());
// });

// it('notify the user when the stock is now available', function () {

//     Notification::fake();
//     $this->seed(RetailerWithProductSeeder::class);

//     mockClientRequest(available: true, price: '£20.0');

//     $this->artisan('track');

//     Notification::assertSentTo(User::first(), ImportantStockUpdate::class);

// });


// it('doesn\'t notify the user when the stock remains unavailable', function () {

//     Notification::fake();

//     $this->seed(RetailerWithProductSeeder::class);

//     mockClientRequest(available: false, price: '£20.0');

//     $this->artisan('track');

//     Notification::assertNothingSent();

// });

// // it shouldn't notify the user when the stock not tracked before

// it('doesn\'t notify the user when the stock not tracked before', function () {

//     Notification::fake();

//     $this->seed(RetailerWithProductSeeder::class);

//     $product = Product::first();

//     $product->stocks->first()->update(['tracked' => false]);

//     mockClientRequest(available: true, price: '£20.0');

//     $this->artisan('track');

//     Notification::assertNothingSent();

// });


it('notify the user when the price drops', function () {

    Event::fake();

    $this->seed(RetailerWithProductSeeder::class);

    $product = Product::first();

    // form  200 to 20
    $product->stocks->first()->update(['price' => 200000, 'in_stock' => true]);

    mockClientRequest(available: true, price: '£20.0');

    // dd($product->stocks->first()->toArray());
    $this->artisan('track');

   Event::assertDispatched(PriceDropped::class);


});
