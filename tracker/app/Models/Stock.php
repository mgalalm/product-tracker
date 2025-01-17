<?php

namespace App\Models;

use App\Events\NowInStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\TrackProduct;
use App\Events\ProductTracked;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];

    // product relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

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

    // protected function price(): Attribute {
    //    return Attribute::make(get: function ($value) {

    //         return  $value;
    //     }, set: function ($value) {
    //         if ($this->price > $value) {
    //             dd('price is less than the value');
    //         }
    //         return $value;
    //    });
    // }
}
