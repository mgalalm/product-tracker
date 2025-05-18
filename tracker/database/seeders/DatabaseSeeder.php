<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Product, Retailer, Type};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            TypeSeeder::class,
        ]);

        $types = Type::all();

        $admin = User::factory()->create([
            'name' => 'John Doe',
            // get the email from the .env file if it exists, otherwise use the default value
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => bcrypt('admin'),
        ]);

        Product::factory()->create([
            'name' => 'Amazon Show 15',
            'user_id' => $admin->id,
            'type_id' => $types->where('slug', 'digital-product')->first()->id,
        ])->stocks()->create([
                    'retailer_id' => Retailer::factory()->create(['name' => 'Amazon UK', 'domain' => 'amazon.co.uk'])->id,
                    'price' => 2000,
                    'url' => 'https://www.amazon.co.uk/echo-show-15/dp/B0C7XNR559/ref=sr_1_1?crid=3UJSAJLBZJ8H2&dib=eyJ2IjoiMSJ9.99iobxumkZ6ww5b6gAqFL3OXj1rMgzI0d-3UY0lxRc3DNK5Ok0MuLwSehRjIZM4we8dOjd908K38PzW6ipa_dfFc_CiOqp_3EG6dXlgcsghw1m0nXgp75pw55J8c2gIcQ8lvZlCylu0_I2FEw4EIuHBKU9Th1OymRJWcAE4baCxhT3MDxMfH1slUzcqtAFmzK2DxGrRHBUfqSDgOc3iGQ_ZZ5NK_Msi4hX-1ukcu4As.Ji1iYNPtPsQNDrABZ1t_mscTIjkvfe5k5PuGh0bftwU&dib_tag=se&keywords=echo+show+15&qid=1739222803&sprefix=Show+15%2Caps%2C88&sr=8-1&ufe=app_do%3Aamzn1.fos.d7e5a2de-8759-4da3-993c-d11b6e3d217f',
                    // 'in_stock' => false,
                    'sku' => 'B0C7XNR559',
                ]);

        Product::factory()->create([
            'name' => 'LOGIK LSD55W23 50/50 Fridge Freezer - White',
            'user_id' => $admin->id,
            'type_id' =>  $types->where('slug', 'subscription')->first()->id,
        ])->stocks()->create(
                [
                    'retailer_id' => Retailer::factory()->create(['name' => 'Currys', 'domain' => 'currys.ie'])->id,
                    'price' => 349,
                    'url' => 'https://www.currys.ie/products/logik-lsd55w23-5050-fridge-freezer-white-10248709.html',
                    'sku' => '10248709',
                ],

            );


        Product::factory()->create(
            [
                'name' => "L'OR Classique Instant Coffee 150g",
                'user_id' => $admin->id,
                'type_id' => $types->where('slug', 'product')->first()->id,
            ]
        )->stocks()->createMany(
                [
                    [
                        'retailer_id' => Retailer::factory()->create(['name' => 'Dunnes', 'domain' => 'dunnesstoresgrocery.com'])->id,
                        'price' => 12.70,
                        'currency' => 'EUR',
                        'url' => "https://www.dunnesstoresgrocery.com/sm/delivery/rsid/428/product/l'or-classique-instant-coffee-150g-id-100274908",
                        'sku' => '100274908',
                    ],
                    [
                        'retailer_id' => Retailer::factory()->create(['name' => 'Tesco', 'domain' => 'tesco.ie'])->id,
                        'price' => 1149,
                        'url' => 'https://www.tesco.ie/groceries/en-IE/products/312095473',
                        'sku' => '312095473',
                    ]
                ]

            );

            // https://www.smythstoys.com/ie/en-ie/outdoor/electric-ride-ons/electric-wheeled/bmw-police-motorcycle-12v-electric-ride-on/p/171375
        Product::factory()->create(
            [
                'name' => "BMW Police Motorcycle 12V Electric Ride On",
                'user_id' => $admin->id,
                'type_id' => $types->where('slug', 'product')->first()->id,
            ]
        )->stocks()->create(
                [
                    'retailer_id' => Retailer::factory()->create(['name' => 'Smyths', 'domain' => 'smythstoys.com'])->id,
                    'price' => 13999,
                    'url' => 'https://www.smythstoys.com/ie/en-ie/outdoor/electric-ride-ons/electric-wheeled/bmw-police-motorcycle-12v-electric-ride-on/p/171375',
                    'sku' => '171375',
                ]

            );

    }
}
