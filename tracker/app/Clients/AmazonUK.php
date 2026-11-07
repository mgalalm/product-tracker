<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;
class AmazonUK implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        // dd(config('crawlers.auth_token') );
        $results = Http::withToken(config('crawlers.auth_token'))->get($this->endpoint($stock))->json();
        $in_stock = Str::contains(Str::lower($results['availability']), 'in stock') === true;
        // $available = $results['availability'] === 'In stock' ? true : false;
        $price  = Money::parse($results['price']);

        return new StockStatus(
            $in_stock,
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        // use base url from Client interface
        // dd(config('crawlers.base_url'));
        return config('crawlers.base_url') . "/amazon-uk/{$stock->sku}";
    }

    public function getProductURLPatterns(): array
    {
        return [
            '/(?:dp|gp\/product)\/([A-Z0-9]+)/'
        ];
    }

}
