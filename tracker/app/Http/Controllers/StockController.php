<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Rules\StockUrlValidation;
use App\Models\Retailer;
use App\Http\Resources\StockResource;

class StockController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, Stock $stock)
    {
        //


        return inertia('Stocks/Edit', [
            'stock' => StockResource::make($stock->load('retailer')),
            'retailers' => Retailer::all(),
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Stock $stock)
    {
        Gate::authorize('update', [$stock, $product]);

        $data = $request->validate([
            'retailer' => ['required', 'exists:retailers,id'],
            'url' => ['required', 'url', new StockUrlValidation],
        ]);

        $product->stocks()->detach($stock);

        $product->stocks()->firstOrCreate(
            [
                'retailer_id' => $data['retailer'],
                'url' => $data['url'],
            ],
            [
                'sku' => $stock->sku,
            ]
        );

        return to_route('products.show', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product , Stock $stock)
    {
        Gate::authorize('delete', [$stock, $product]);

        $product->stocks()->detach($stock);
        return to_route('products.show', $product->id);
    }
}
