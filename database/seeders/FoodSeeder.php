<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ GUNAKAN updateOrCreate() - aman dijalankan berulang
        User::updateOrCreate(
            ['email' => 'admin@catering.com'], // Cari berdasarkan email
            [   // Jika tidak ada, buat baru. Jika ada, update data ini
                'name' => 'Admin Catering',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            'phone' => '081234567890',
            ]
);

        // Create Customer User
        User::updateOrCreate(
            ['email' => 'customer@catering.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081234567891',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
            ]
        );

        // Create Categories
        $categories = [
            ['name' => 'Nasi Box', 'description' => 'Paket nasi box praktis untuk berbagai acara', 'is_active' => true],
            ['name' => 'Prasmanan', 'description' => 'Layanan catering prasmanan', 'is_active' => true],
            ['name' => 'Snack Box', 'description' => 'Aneka snack box untuk meeting & acara', 'is_active' => true],
            ['name' => 'Paket Pernikahan', 'description' => 'Paket catering spesial pernikahan', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Create Foods
        $foods = [
            [
                'category_id' => 1,
                'name' => 'Nasi Box Ayam Goreng',
                'description' => 'Nasi putih, ayam goreng krispi, sambal, lalapan, kerupuk, acar',
                'price' => 35000,
                'stock' => 100,
                'is_available' => true,
                'is_popular' => true,
                'image' => null,
            ],
            [
                'category_id' => 1,
                'name' => 'Nasi Box Rendang',
                'description' => 'Nasi putih, rendang daging sapi, sambal hijau, lalapan, kerupuk',
                'price' => 40000,
                'stock' => 80,
                'is_available' => true,
                'is_popular' => true,
                'image' => null,
            ],
            [
                'category_id' => 1,
                'name' => 'Nasi Box Ikan Bakar',
                'description' => 'Nasi putih, ikan bakar sambal matah, plecing kangkung, kerupuk',
                'price' => 38000,
                'stock' => 60,
                'is_available' => true,
                'is_popular' => false,
                'image' => null,
            ],
            [
                'category_id' => 2,
                'name' => 'Paket Prasmanan A (Min 50 porsi)',
                'description' => '5 lauk pauk, 2 sayur, nasi putih, buah, es buah. Harga per porsi',
                'price' => 45000,
                'stock' => 500,
                'is_available' => true,
                'is_popular' => true,
                'image' => null,
            ],
            [
                'category_id' => 2,
                'name' => 'Paket Prasmanan B (Min 50 porsi)',
                'description' => '6 lauk pauk, 3 sayur, nasi putih, buah, puding. Harga per porsi',
                'price' => 55000,
                'stock' => 500,
                'is_available' => true,
                'is_popular' => false,
                'image' => null,
            ],
            [
                'category_id' => 3,
                'name' => 'Snack Box Mix 1',
                'description' => 'Risoles, pastel, lumpia, lemper (4 pcs)',
                'price' => 20000,
                'stock' => 200,
                'is_available' => true,
                'is_popular' => true,
                'image' => null,
            ],
            [
                'category_id' => 3,
                'name' => 'Snack Box Mix 2',
                'description' => 'Bakwan, tahu isi, tempe mendoan, cireng (4 pcs)',
                'price' => 18000,
                'stock' => 200,
                'is_available' => true,
                'is_popular' => false,
                'image' => null,
            ],
            [
                'category_id' => 4,
                'name' => 'Paket Pernikahan Silver',
                'description' => 'Prasmanan lengkap untuk 500 tamu, dekorasi meja buffet, 2 waiter',
                'price' => 25000000,
                'stock' => 10,
                'is_available' => true,
                'is_popular' => true,
                'image' => null,
            ],
            [
                'category_id' => 4,
                'name' => 'Paket Pernikahan Gold',
                'description' => 'Prasmanan premium untuk 500 tamu, dekorasi full, 4 waiter, live cooking',
                'price' => 40000000,
                'stock' => 5,
                'is_available' => true,
                'is_popular' => false,
                'image' => null,
            ],
        ];

        foreach ($foods as $food) {
            Food::updateOrCreate(
                ['name' => $food['name']],
                $food
            );
        }
    }
}
