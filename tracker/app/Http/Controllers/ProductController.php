<?php

namespace App\Http\Controllers;

use App\Rules\ProductUrlValidation;
use Gate;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\Retailer;
use App\Http\Resources\RetailerResource;
use Illuminate\Support\Facades\DB;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Products/Index', [
            'products' => ProductResource::collection(Product::latest()->latest('id')->paginate()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Products/Create', [
            'retailers' => fn() => RetailerResource::collection(Retailer::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'retailer' => ['required', 'exists:retailers,id'],
            'stockUrl' => ['required', 'url', new ProductUrlValidation],
        ]);

        // get sku from session
        $sku = session()->get('sku');

        // create product
        $product = $request->user()->products()->create([
            'name' => $data['name'],
        ]);

        // create stock
        $product->stocks()->firstOrCreate([
            'sku' => $sku,
            'retailer_id' => $data['retailer'],
        ] ,[
            'price' => 0,
            'url' => $data['stockUrl'],
            'in_stock' => false,
            'retailer_id' => $data['retailer'],
            'sku' => $sku,
        ]);

        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // load the stocks relationship
        $product->load('stocks');

        return inertia('Products/Show', [
            'product' => fn() => ProductResource::make($product),
            'stocks' => fn() => StockResource::collection($product->stocks()->with('retailer')->latest('updated_at')->paginate()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        Gate::authorize('delete', $product);

        DB::transaction(function () use ($product) {
            $stocks = $product->stocks;

            $product->stocks()->detach();

            $product->delete();

            // delete the stock associated with the product as long it's not associated with another product
            $stocks->each(function ($stock) {
                if ($stock->products->isEmpty()) {
                    $stock->delete();
                }
            });
        });

        return to_route('products.index');
    }
}
