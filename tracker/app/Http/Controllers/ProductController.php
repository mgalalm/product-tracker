<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Models\Retailer;
use App\Http\Resources\RetailerResource;


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
        // dd($request->all());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stockUrl' => ['required', 'url'],
            'retailer' => ['required', 'exists:retailers,id'],
        ]);

        // dd($data);

        $product = Product::make($data)
            ->user()->associate($request->user())
            // ->retailer()->associate($data['retailerId'])
            ->save();

        //save stock
        // $product->stocks()->create([
        //     'price' => 0,
        //     'url' => $data['stockUrl'],
        //     'in_stock' => false,
        //     'retailer_id' => $data['retailer'],
        // ]);


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
    public function destroy(string $id)
    {
        //
    }
}
