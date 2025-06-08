<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // Nasi Uduk: varian contoh
        Product::create([
            'name' => 'Nasi Uduk Original',
            'section' => 'nasi_uduk',
            'price' => 10000,
            'stock' => 50,
            'image_path' => 'products/nasi_uduk_original.jpg',
        ]);
        Product::create([
            'name' => 'Nasi Uduk Rendang',
            'section' => 'nasi_uduk',
            'price' => 15000,
            'stock' => 30,
            'image_path' => 'products/nasi_uduk_rendang.jpg',
        ]);

        // Aneka Semur: varian contoh
        Product::create([
            'name' => 'Semur Daging',
            'section' => 'aneka_semur',
            'price' => 20000,
            'stock' => 20,
            'image_path' => 'products/semur_daging.jpg',
        ]);
        Product::create([
            'name' => 'Semur Telur',
            'section' => 'aneka_semur',
            'price' => 8000,
            'stock' => 40,
            'image_path' => 'products/semur_telur.jpg',
        ]);
    }
}
