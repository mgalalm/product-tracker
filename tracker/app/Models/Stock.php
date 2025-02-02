<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\TrackProduct;

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
        TrackProduct::dispatch($this)->delay(now()->addMinutes(4));
    }

    // products relationship
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
