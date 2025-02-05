<?php

namespace App\Models;

use App\Clients\StockStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\TrackProduct;
use Illuminate\Support\Sleep;
use Cknow\Money\Money;


class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];

    // retailer relationship
    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    // cast in_stock to boolean
    protected $casts = [
        'in_stock' => 'boolean',
    ];

    public function track()
    {
        if ($this->retailer->client() === null) {
            return;
        }
        // dispatch job
        // sleep for random between 4 to 5 minutes
        Sleep::for(rand(3, 5))->minutes();
        TrackProduct::dispatch($this)->delay(now()->addMinutes(1));
        // TrackProduct::dispatch($this);
    }

    // products relationship
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }


    public function nowInStock(StockStatus $status): bool
    {
        return $this->tracked && !$this->in_stock && $status->available;
    }

    public function priceDropped(StockStatus $status): bool
    {
        return $this->tracked && $this->in_stock && $status->available && $this->price > $status->price->getAmount();
    }

    public function getFormattedPriceAttribute()
    {
        return Money::parse($this->price, $this->currency)->format();
        // return Attribute::make(
        //     get: fn (mixed $value, array $attributes) => new Money(
        //         $attributes['price'], $attributes['currency'])
        // );
    }

}
