<?php

namespace App\Clients;
use App\Clients\StockStatus;
use App\Models\Stock;

interface Client
{
    // define a constant for crawler base url
    public const BASE_URL = 'http://127.0.0.1:5000/scrape';

    public function checkAvailability(Stock $stock): StockStatus;

    public function endpoint(Stock $stock): string;
}
