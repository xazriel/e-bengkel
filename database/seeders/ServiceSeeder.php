<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $services = [
        [
            'name' => 'Ganti Oli & Cek Rutin',
            'estimated_duration' => 20, // 20 Menit
            'price' => 50000,
        ],
        [
            'name' => 'Servis CVT (Motor Matic)',
            'estimated_duration' => 45, // 45 Menit
            'price' => 85000,
        ],
        [
            'name' => 'Servis Injeksi / Karburator',
            'estimated_duration' => 30, // 30 Menit
            'price' => 60000,
        ],
        [
            'name' => 'Ganti Ban Luar',
            'estimated_duration' => 15, // 15 Menit
            'price' => 35000,
        ],
        [
            'name' => 'Servis Besar / Turun Mesin',
            'estimated_duration' => 180, // 3 Jam
            'price' => 350000,
        ],
    ];

    foreach ($services as $service) {
        \App\Models\Service::updateOrCreate(['name' => $service['name']], $service);
    }}
}