<?php
use App\Models\User;
use function Pest\Laravel\actingAs;
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
