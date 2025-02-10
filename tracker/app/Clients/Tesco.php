<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;

class Tesco implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results =  Http::get($this->endpoint($stock))->json();
        // dd($results);

        // if price is N/A set the value to 0 otherwise parse the price
        $price = $results['price'] === 'N/A' ? Money::EUR(0) : Money::parse($results['price']);
        $in_stock = Str::contains(Str::lower($results['availability']), 'out of stock') === false;

        return new StockStatus(
            $in_stock,
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        return $this::BASE_URL . "/tesco/{$stock->sku}";
    }

    public function getProductURLPatterns(): array
    {
        return [
            '/(?:products)\/.*-(\d+)/'
        ];
    }
}
