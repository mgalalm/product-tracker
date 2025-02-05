<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Product, Retailer, Stock};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $admin = User::factory()->create([
            'name' => 'John Doe',
            // get the email from the .env file if it exists, otherwise use the default value
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => bcrypt('admin'),
        ]);

        Product::factory()->create([
            'name' => 'Neoteck Non-Contact AC Voltage Tester Pen',
            'user_id' => $admin->id,
        ])->stocks()->create([
            'retailer_id' => Retailer::factory()->create(['name' => 'Amazon UK', 'domain' => 'amazon.co.uk'])->id,
            'price' => 2000,
            'url' => 'https://www.amazon.co.uk/Neoteck-Non-Contact-12-1000V-Detector-Flashlight/dp/B01JYCVU2Q',
            'in_stock' => false,
            'sku' => 'B01JYCVU2Q',
        ]);

        Product::factory()->create([
            'name' => 'LOGIK LSD55W23 50/50 Fridge Freezer - White',
            'user_id' => $admin->id,
        ])->stocks()->create([
            'retailer_id' => Retailer::factory()->create(['name' => 'Currys', 'domain' => 'currys.ie'])->id,
            'price' => 349,
            'url' => 'https://www.currys.ie/products/logik-lsd55w23-5050-fridge-freezer-white-10248709.html',
            'in_stock' => false,
            'sku' => '10248709',
        ]);

        Product::factory()->create(
            [
                'name' => "L'OR Classique Instant Coffee 150g",
                'user_id' => $admin->id,
            ]
        )->stocks()->create([
            'retailer_id' => Retailer::factory()->create(['name' => 'Dunnes', 'domain' => 'dunnesstoresgrocery.com'])->id,
            'price' => 12.70,
            'currency' => 'EUR',
            'url' => "https://www.dunnesstoresgrocery.com/sm/delivery/rsid/428/product/l'or-classique-instant-coffee-150g-id-100274908",
            'in_stock' => false,
            'sku' => '100274908',
        ]);
    }
}
