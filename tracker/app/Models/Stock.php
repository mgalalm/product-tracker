<?php

namespace App\Models;

use App\Clients\StockStatus;
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
        Sleep::for(rand(1, 2))->minutes();
        TrackProduct::dispatch($this)->delay(now()->addMinutes(1));
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
    }

     // is new stock if it's not tracked and created less than 5 minutes ago
    public function getIsNewAttribute()
    {

        return !$this->tracked && $this->created_at->gt(now()->subMinutes(5));
    }

    // get tacked attribute
    public function getTrackedAttribute()
    {
        return !is_null($this->in_stock);
    }

    // get status attribute
    public function getStatusAttribute()
    {
        if ($this->is_new) {
            return 'Not Tracked Yet';
        }

        if (!$this->tracked) {
            return 'Processing';
        }

        return $this->in_stock ? 'In Stock' : 'Out of Stock';
    }

}
