<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Stock;
use App\Events\{NowInStock,ProductTracked};

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

        if (!$this->stock->in_stock && $status->available) {
            // notify user
            NowInStock::dispatch($this->stock);
        }

        $this->stock->update([
            'in_stock' => $status->available,
            'price' => $status->price->getAmount(),
            'currency' => $status->price->getCurrency(),
        ]);

        // dispatch event
        ProductTracked::dispatch($this->stock);

    }
}
