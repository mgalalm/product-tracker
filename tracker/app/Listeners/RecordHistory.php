<?php

namespace App\Listeners;

use App\Events\ProductTracked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\History;


class RecordHistory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductTracked $event): void
    {
        $stock = $event->stock;
        History::create([
            'price' => $stock->price,
            'in_stock' => $stock->in_stock,
            'product_id' => $stock->product_id,
            'stock_id' => $stock->id,
        ]);
    }
}
