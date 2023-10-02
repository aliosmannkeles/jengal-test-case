<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\DiscountRule;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Customer::insert([
            [
                'name' => 'Türker Jöntürk',
                'since' => '2014-06-28',
                'email' => 'test@test.com',
                'revenue' => 492.12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kaptan Devopuz',
                'since' => '2015-01-15',
                'email' => 'test1@test.com',
                'revenue' => 1505.95,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'İsa Sonuyumaz',
                'since' => '2016-02-11',
                'email' => 'test2@test.com',
                'revenue' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Category::insert([
            [
                'name' => 'Elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Giyim',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Product::insert([
            [
                'name' => "Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti",
                'category_id' => 1,
                'price' => 320.75,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Reko Mini Tamir Hassas Tornavida Seti 32'li",
                'category_id' => 1,
                'price' => 150.50,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Viko Karre Anahtar - Beyaz",
                'category_id' => 2,
                'price' => 200.28,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Legrand Salbei Anahtar, Alüminyum",
                'category_id' => 2,
                'price' => 100.80,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Schneider Asfora Beyaz Komütatör",
                'category_id' => 2,
                'price' => 120.95,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


        DiscountRule::insert([
            [
                'category_id' => null,
                'type' => 'totalAmount',
                'reason' => '10_PERCENT_OVER_1000',
                'threshold' => '1000',
                'action_type' => 'percent',
                'discount' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'type' => 'totalCategoryProductCount',
                'reason' => '6_BUY_1_FREE',
                'threshold' => '6',
                'action_type' => 'fixed',
                'discount' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'type' => 'totalCategoryCount',
                'reason' => '2_PERCENT_OVER_20',
                'threshold' => '2',
                'action_type' => 'percent',
                'discount' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
