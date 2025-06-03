<?php

namespace App\Http\Controllers;

use App\Http\Resources\TypeResource;
use App\Rules\StockUrlValidation;

use Illuminate\Http\Request;
use App\Models\ {Product, User, Retailer, Type};
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockResource;
use App\Http\Resources\RetailerResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Gate;
use Str;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, Type $type = null)
    {
        Gate::authorize('viewAny', Product::class);

        $products = $request->user()->products()
        ->select('id', 'name', 'created_at', 'type_id')
        ->with('type:id,name,slug' )
        ->when($type, fn($query) => $query->where('type_id', $type->id))
        ->latest('id')
        ->paginate();

        $productResources = ProductResource::collection($products);

        if ($request->wantsJson()) {
            return $productResources;
        }

        return inertia('Products/Index', [
            'products' => $productResources,
            'types' => fn() => TypeResource::collection(Type::all()),
            'selectedType' => fn() => $type ? TypeResource::make($type) : null, //only load products when Moving through pagination rather than also loading the selected topic data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        Gate::authorize('create', Product::class);

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

        return redirect($product->showRoute())
            ->banner("Product {$product->name} has been created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        Gate::authorize('view', $product);

        if (!Str::contains($product->showRoute(), $request->path())) {
            return redirect($product->showRoute($request->query()), 301);
        }

        $product->load('stocks');

        if ($request->wantsJson()) {
            return ProductResource::make($product);
        }

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

        return to_route('products.index')
            ->banner("Product {$product->name} has been deleted successfully.");
    }
}
