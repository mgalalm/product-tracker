<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;
class AmazonIE implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {

        $results = Http::get($this->endpoint($stock))->json();
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
        return $this::BASE_URL . "/amazon-ie/{$stock->sku}";
    }

    public function getProductURLPatterns(): array
    {
        return [
            '/(?:dp|gp\/product)\/([A-Z0-9]+)/'
        ];
    }

}
