<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Food;
use App\Models\Voucher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            FoodSeeder::class,
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@catering.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create Customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@catering.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567891',
            'address' => 'Jl. Contoh No. 123',
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Nasi Box', 'description' => 'Paket nasi box untuk acara'],
            ['name' => 'Prasmanan', 'description' => 'Layanan prasmanan'],
            ['name' => 'Snack Box', 'description' => 'Aneka snack box'],
            ['name' => 'Paket Pernikahan', 'description' => 'Paket catering pernikahan'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Foods
        $foods = [
            [
                'category_id' => 1,
                'name' => 'Nasi Box Ayam Goreng',
                'description' => 'Nasi putih, ayam goreng, sambal, lalapan, kerupuk',
                'price' => 35000,
                'stock' => 100,
                'is_popular' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Nasi Box Rendang',
                'description' => 'Nasi putih, rendang daging, sambal, lalapan',
                'price' => 40000,
                'stock' => 80,
                'is_popular' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Paket Prasmanan A',
                'description' => 'Minimal 50 porsi, 5 lauk, 2 sayur, nasi, buah',
                'price' => 45000,
                'stock' => 500,
                'is_popular' => false,
            ],
            [
                'category_id' => 3,
                'name' => 'Snack Box Mix',
                'description' => 'Aneka snack: risoles, pastel, lumpia, lemper',
                'price' => 20000,
                'stock' => 200,
                'is_popular' => true,
            ],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }

        // Create Voucher
        Voucher::create([
            'code' => 'DISKON10',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 100000,
            'max_discount' => 50000,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
            'usage_limit' => 100,
            'is_active' => true,
        ]);
    }
}
