<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;


Route::middleware(['throttle:api'])->group(function () {

    Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware('auth:sanctum');




    Route::resource('products', ProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy', 'show'])
        ->middleware('auth:sanctum');

    Route::get('products/{type?}', [ProductController::class, 'index'])
        ->name('products.index')
        ->middleware('auth:sanctum');

    Route::post('/user', [AuthController::class, 'login']);

});




