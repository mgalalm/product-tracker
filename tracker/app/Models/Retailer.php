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
