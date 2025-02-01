<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;

class Currys implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results =  Http::get($this->endpoint($stock))->json();

        $price = Money::parse($results['price']);
        return new StockStatus(
            $results['availability'],
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        return $this::BASE_URL . "/currys/{$stock->sku}.html";
    }

    public function getProductURLPatterns(): array
    {
        return [
            '/(?:products)\/.*-(\d+)(?=\.html)/'
        ];
    }
}
