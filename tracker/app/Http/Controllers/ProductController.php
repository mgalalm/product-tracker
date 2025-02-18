<?php

namespace App\Http\Controllers;

use App\Rules\StockUrlValidation;

use Illuminate\Http\Request;
use App\Models\ {Product};
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\Retailer;
use App\Http\Resources\RetailerResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Gate;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return inertia('Products/Index', [
            'products' => ProductResource::collection($request->user()->products()->latest()->latest('id')->paginate()),
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
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'stocks' => ['required', 'array'],

            'stocks.*.retailer' => ['bail', 'required',
                'required',
                Rule::exists('retailers', 'id')],
            'stocks.*.url' => ['required', 'url', new StockUrlValidation],
            'stocks.*.sku' => ['sometimes', 'required'],
        ]);

        $product = $request->user()->products()->create([
            'name' => $data['name'],
        ]);

        collect($data['stocks'])->each(function ($stock) use ($product) {
            $product->stocks()->firstOrCreate(
                [
                    'sku' => $stock['sku'],
                    'retailer_id' => $stock['retailer'],
                ],
                [
                    'url' => $stock['url'],
                ]
            );
        });

        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);

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
    public function update(Request $request, Product $product)
    {

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
