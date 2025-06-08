<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // biar bisa di-seed berulang tanpa nambahin terus
            [
                'name' => 'Admin Bazar',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'), // ganti sesuai kebutuhan
                'is_admin' => true, // pastikan di tabel users ada kolom 'role'
            ]
        );
    }
}
