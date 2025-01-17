<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
class AmazonUK implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {

        $results = Http::get($this->endpoint($stock))->json();

        $available = $results['available'] === 'In stock' ? true : false;
        $price  = Money::parse($results['price']);

        return new StockStatus(
            $available,
            $price
        );
    }

    public function endpoint(Stock $stock): string
    {
        // use base url from Client interface
        return $this::BASE_URL . "/amazon-uk/{$stock->sku}";
    }

}
