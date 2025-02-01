<?php

namespace App\Models;

use Facades\App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function addStock(Product $product, Stock $stock)
    {

        $stock = $this->stock()->create($stock->toArray());
        $product->stocks()->attach($stock->id);
    //    $this->stock()->save($stock);
    //    dd($product->stocks()->get());

    //    dd($product->w
        // $product->retailer_id = $this->id;
        // $product->save();
        // $stock->product_id = $product->id;
        // $stock->save();
        // $product->stocks()->save($stock);
        // $product->stocks()->attach($stock)->save();
    //    $product->save();
        // $stock->products()->attach($product)->save();

        // $stock->product_id = $product->id;
        // $this->stock()->save($stock);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function client()
    {
        return ClientFactory::make($this->name);
    }

}
