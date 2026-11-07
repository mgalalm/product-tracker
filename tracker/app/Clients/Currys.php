<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;

class Currys implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::withToken(config('crawlers.auth_token'))->get($this->endpoint($stock))->json();

        $price = Money::parse($results['price']);
        $in_stock = Str::lower($results['availability']) !== Str::lower('out of stock');

        return new StockStatus(
            $in_stock,
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        return config('crawlers.base_url') . "/currys/{$stock->sku}.html";
    }

    public function getProductURLPatterns(): array
    {
        return [
            '/(?:products)\/.*-(\d+)(?=\.html)/'
        ];
    }
}
