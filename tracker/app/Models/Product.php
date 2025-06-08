<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cknow\Money\Money;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'type_id'];
    public function inStock()
    {
        return $this->stocks()->where('in_stock', true)->exists();
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    // stock relationship
    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }

    public function track()
    {
        $this->load(['stocks.retailer'])->stocks->each->track();
    }

    // history
    public function history()
    {
        return $this->hasMany(History::class);
    }

    // type
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getPriceRangeAttribute()
    {
        if (!$this->relationLoaded('stocks')) {
            $this->load('stocks');
        }

        $stocks = $this->stocks;

        if ($stocks->isEmpty()) {
            return null;
        }

        $minPrice = $stocks->min('price');
        $maxPrice = $stocks->max('price');

        // Get currency from first stock
        $currency = $stocks->first()->currency;

        // Convert to Money objects using the currency from first stock
        $minMoney = Money::$currency($minPrice);
        $maxMoney = Money::$currency($maxPrice);

        if ($minPrice === $maxPrice) {
            return $minMoney->format();
        }

        return $minMoney->format() . ' - ' . $maxMoney->format();
    }

    public function showRoute(array $params = []): string
    {
        return route('products.show', [$this, Str::slug($this->name), ...$params]);
    }

}
