<?php

use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration screen cannot be rendered if support is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
})->skip(function () {
    return Features::enabled(Features::registration());
}, 'Registration support is enabled.');

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password1$',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

// test user can't register with weak password
test('new users can\'t register with weak password', function ($password) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => $password,
        'password_confirmation' => $password,
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);
    $this->assertGuest();
    // $response->assertRedirect(route('register'));
    // $response->assertSessionHasErrors([
    //     'password' => 'The password must be at least 10 characters long and contain at least one uppercase letter, one number, and one special character.',
    // ]);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.')
->with([
    'password' => 'password',
    'password' => 'Password',
    'password' => 'Password1',
    'password' => 'password1$',
]);

