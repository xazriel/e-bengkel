<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Master Layanan (Daftar Harga & Durasi Standar)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->integer('estimated_duration'); // Durasi standar dalam MENIT
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // 2. Tabel Booking & Tracking Waktu (Sumber Data BI)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (siapa yang menginput/login)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Relasi ke Jenis Layanan
            $table->foreignId('service_id')->constrained();
            
            // Detail Kendaraan & Pelanggan
            $table->string('customer_name')->nullable();
            $table->string('motor_model')->nullable();
            
            // Tracking Waktu
            $table->dateTime('booking_time'); // Waktu input masuk
            $table->dateTime('estimated_finish_at'); // Target selesai (Sistem)
            
            // Data Aktual (Diisi oleh Mekanik - Bahan utama BI)
            $table->dateTime('actual_start_at')->nullable(); // Kapan mekanik klik "Mulai"
            $table->dateTime('actual_finish_at')->nullable(); // Kapan mekanik klik "Selesai"
            
            // Status Alur Kerja
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel dengan urutan terbalik untuk menghindari error Foreign Key
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('services');
    }
};