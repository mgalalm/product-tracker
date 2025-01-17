<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $products = Product::all();

        $this->output->progressStart($products->count());

        $products->each(function (Product $product) {
            $product->track();
            $this->output->progressAdvance();
        });

        $this->output->progressFinish();
        $this->showResults();
    }

    private function showResults()
    {
        $data = Product::leftJoin('stocks', 'stocks.product_id', '=', 'products.id')
            ->get(['products.name', 'stocks.price', 'stocks.url', 'stocks.in_stock']);

        $this->table(['Name', 'Price', 'URL', 'In Stock'], $data);
    }
}
