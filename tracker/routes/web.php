<?php

use App\Http\Controllers\StockController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Resources\ProductResource;
use App\Models\Product;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('products.stocks', StockController::class);

    // show with a slug
    Route::get('products/{product}/{slug}', [ProductController::class, 'show'])->name('products.show');
});


Route::get('/test', function () {
    phpinfo();
    return 'test';
});
