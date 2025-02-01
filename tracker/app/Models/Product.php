<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
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
        $this->load(['stocks'])->stocks->each->track();
    }

    // history
    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
