<?php

use App\Http\Controllers\StockController;
use App\Http\Resources\{
    ProductResource,
    RetailerResource,
    StockResource,
    UserResource
};
use App\Models\{
    Product,
    Retailer,
    Stock,
    User
};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;


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
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{product}/stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/stocks', [ProductController::class, 'storeStock'])->name('products.stocks.store');


// test route return user
Route::get('/test', function () {
    return StockResource::collection(Stock::with('product', 'retailer')->paginate(1));
});
