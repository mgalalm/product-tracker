<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Stock;
use App\Events\{NowInStock,ProductTracked, PriceDropped};

class TrackProduct implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Stock $stock)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $status = $this->stock->retailer->client()
            ->checkAvailability($this->stock);


        // product was not in stock and now is in
        if ($this->stock->nowInStock($status)) {
            // notify user
            NowInStock::dispatch($this->stock);
        }

        // if the price of the stock price drops trigger PriceDropped event
        if ($this->stock->priceDropped($status)) {
            // notify user
            PriceDropped::dispatch($this->stock);
        }

        $this->stock->update([
            'in_stock' => $status->available,
            'price' => $status->price->getAmount(),
            'currency' => $status->price->getCurrency(),
            'tracked' => true,
        ]);

        // dispatch event
        ProductTracked::dispatch($this->stock);

    }
}
