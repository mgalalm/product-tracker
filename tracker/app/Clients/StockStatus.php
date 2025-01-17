<?php

namespace App\Clients;

use Cknow\Money\Money;

class StockStatus
{
    // cast In stock to boolean

    public $available;
    public $price;

    public function __construct(bool $available, Money $price)
    {
        $this->available = $available;
        $this->price = $price;
    }
}
