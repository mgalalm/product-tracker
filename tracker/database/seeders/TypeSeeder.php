<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'product',
                'name' => 'Product',
                'description' => 'A product is a tangible item that is manufactured or produced for sale.'
            ],
            [
                'slug' => 'service',
                'name' => 'Service',
                'description' => 'A service is an intangible offering that involves a deed, performance, or effort.'
            ],
            [
                'slug' => 'digital-product',
                'name' => 'Digital Product',
                'description' => 'A digital product is a product that is delivered electronically.'
            ],
            [
                'slug' => 'subscription',
                'name' => 'Subscription',
                'description' => 'A subscription is a business model where customers pay a recurring fee for access to a product or service.'
            ],

        ];

        Type::upsert(
            $data,
            ['slug']
        );
    }
}
