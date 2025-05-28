<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;

class Supervalu implements Client
{


    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::get($this->endpoint($stock))->json();

        // if name contains "unavailable" or "missing" return false
        if (Str::contains(Str::lower($results['name']), 'unavailable') || Str::contains(Str::lower($results['name']), 'missing')) {
            return new StockStatus(false, Money::EUR(0));
        }

        $price = Money::parse($results['price']);
        $in_stock = Str::lower($results['availability']) !== Str::lower('out of stock');


        return new StockStatus(
            $in_stock,
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        return self::BASE_URL . "/supervalu/{$stock->sku}";
    }

    public function getProductURLPatterns(): array
    {
        // Example: /shop/groceries/coffee/nescafe-gold-blend-100g/p-5011546488182
        return [
            '/-p-(\d+)$/'
        ];
    }
}
