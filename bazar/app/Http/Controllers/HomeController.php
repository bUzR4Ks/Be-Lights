<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    // Halaman utama: tampilkan dua section summary (link saja)
    public function index()
    {
        return view('home.index');
    }

    // Tampilkan produk di satu section (termasuk bundling)
    public function section($section)
    {
        if ($section === 'multiple') {
            // Ambil produk yang digunakan dalam bundling
            $products = Product::whereIn('id', [1, 3, 4, 5])->get();

            // Kirim array bundling ke view agar bisa diatur tampilannya
            $bundlings = [
                'Bundling 1' => [1, 3], // Nasi Uduk Original + Semur Ayam
                'Bundling 2' => [1, 4], // Nasi Uduk Original + Semur Telur
                'Bundling 3' => [1, 5], // Nasi Uduk Original + Semur Tahu Kentang
            ];

            return view('home.section', compact('products', 'section', 'bundlings'));
        }

        // Untuk section biasa
        $products = Product::where('section', $section)->get();
        return view('home.section', compact('products', 'section'));
    }
}
