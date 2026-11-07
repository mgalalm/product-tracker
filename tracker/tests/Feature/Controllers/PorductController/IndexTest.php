<?php

use App\Http\Resources\ProductResource;
use App\Http\Resources\TypeResource;
use function Pest\Laravel\get;
use Database\Seeders\RetailerWithProductSeeder;

use App\Models\Product;
use App\Models\User;
use App\Models\Type;

use function Pest\Laravel\actingAs;

// non authenticated user can't access the products index
it('non authenticated user can\'t access the products index', function () {
    get(route('products.index'))
        ->assertRedirect(route('login'));
});

it('should return the correct component', function () {
    $user = User::factory()->create();
    actingAs($user)->get(route('products.index'))
        ->assertComponent('Products/Index');
});

it('passes the products to the view', function () {
    $this->seed(RetailerWithProductSeeder::class);

    $products = Product::all();

    $products->load(['type:id,name,slug']);

    actingAs(RetailerWithProductSeeder::$user)->get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection($products->reverse()));
});


it('can filter to a type', function () {

    $user = User::factory()->create();
    $general = Type::factory()->create();

    $products = Product::factory(2)->create([
        'type_id' => $general->id,
        'user_id' => $user->id,
    ]);

    $products->load(['user', 'type:id,name,slug']);
    actingAs($user)->get(route('products.index', ['type' => $general]))
        ->assertHasPaginatedResource('products', ProductResource::collection($products->reverse()) );
});

// new user can't access the products index if he has no products
it('new user can\'t access the products index if he has no products', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $user = User::factory()->create();
    actingAs($user)->get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection([]));;
});


// passes the selected type to the view
it('passes the selected type to the view', function () {

    $user = User::factory()->create();
    $general = Type::factory()->create();
    actingAs($user)->get(route('products.index', ['type' => $general]))
        ->assertHasResource('selectedType', TypeResource::make($general));
});


it('passes types to the view', function () {
    $this->seed(RetailerWithProductSeeder::class);

    $user = RetailerWithProductSeeder::$user;

    $types =Type::all();
    // $types = Type::factory(2)->create();
    actingAs($user)->get(route('products.index'))
        ->assertHasResource('types', TypeResource::collection($types));
});
