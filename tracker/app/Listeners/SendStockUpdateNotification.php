<?php

namespace App\Listeners;

use App\Events\NowInStock;
use App\Events\PriceDropped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;

class SendStockUpdateNotification
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
    public function handle(NowInStock|PriceDropped $event): void
    {
        User::first()->notify(new ImportantStockUpdate($event->stock));
    }
}
