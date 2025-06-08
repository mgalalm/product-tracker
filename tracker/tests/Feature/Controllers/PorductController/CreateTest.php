<?php
use App\Http\Resources\TypeResource;
use App\Http\Resources\RetailerResource;
use App\Models\Retailer;
use App\Models\User;
use function Pest\Laravel\actingAs;
use App\Models\Type;
// it show create product form
it('can show create product form', function () {
    $user = User::factory()->create();
    actingAs($user)->get(route('products.create'))
        ->assertComponent('Products/Create');
});


// doesn't show create product form for guest
it('doesn\'t show create product form for guest', function () {
    $this->get(route('products.create'))
        ->assertRedirect(route('login'));
});

it('returns the correct component', function () {
    $user = User::factory()->create();
    actingAs($user)->get(route('products.create'))
        ->assertComponent('Products/Create');
});

// it passes retailers to the view
it('passes retailers to the view', function () {
    $user = User::factory()->create();
    Retailer::factory()->count(3)->create();
    actingAs($user)->get(route('products.create'))
        ->assertComponent('Products/Create')
        ->assertHasResource('retailers', RetailerResource::collection(Retailer::all()));
});

it('passes types to the view', function () {
    $user = User::factory()->create();
    Type::factory()->count(3)->create();
    actingAs($user)->get(route('products.create'))
        ->assertComponent('Products/Create')
        ->assertHasResource('types', TypeResource::collection(Type::all()));
});

