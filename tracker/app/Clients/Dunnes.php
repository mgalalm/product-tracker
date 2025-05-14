<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use Cknow\Money\Money;
use Illuminate\Support\Str;

class Dunnes implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results =  Http::get($this->endpoint($stock))->json();

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
        return $this::BASE_URL . "/dunnes/{$stock->sku}";
    }

    public function getProductURLPatterns(): array
    {
        // /sm/delivery/rsid/428/product/l'or-classique-instant-coffee-150g-id-100274908
        return [
            '/-(\d+)$/'
        ];
    }
}
