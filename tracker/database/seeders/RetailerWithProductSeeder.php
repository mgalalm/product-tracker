<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, Retailer, Stock, User, Type};
use Database\Seeders\TypeSeeder;

class RetailerWithProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(TypeSeeder::class);

        $types = Type::all();

        $user = User::factory()->create(['email' => 'mgalal@example.com']);

        $my_book = Product::create(['name' => "Zayn and Grandad\s van", 'user_id' => $user->id, 'type_id' => $types->where('slug', 'general')->first()->id]);

        $amazon_uk = Retailer::create(['name' => 'Amazon UK', 'domain' => 'amazon.co.uk']);

        $stock = new Stock([
            'price' => 2000,
            'url' => 'http://www.amazon.uk/dp/12345678',
            'in_stock' => false,
            'sku' => '12345',
        ]);

        $amazon_uk->addStock($my_book, $stock);

    }
}
