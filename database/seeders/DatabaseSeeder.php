<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin default
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@66garage.com'],
            [
                'name'     => 'Admin 66 Garage',
                'email'    => 'admin@66garage.com',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
            ]
        );

        // Buat akun mekanik contoh
        \App\Models\User::updateOrCreate(
            ['email' => 'mekanik@66garage.com'],
            [
                'name'     => 'Mekanik Handal',
                'email'    => 'mekanik@66garage.com',
                'password' => bcrypt('mekanik123'),
                'role'     => 'mekanik',
            ]
        );

        // Panggil ServiceSeeder
        $this->call([
            ServiceSeeder::class,
        ]);
    }
}