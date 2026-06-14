# 66 Garage - Sistem Booking & Dashboard BI Bengkel

Aplikasi web berbasis **Laravel**, **Livewire Volt**, dan **Tailwind CSS** yang dirancang untuk mengelola antrean booking servis motor secara real-time. Sistem ini dilengkapi dengan **Panel Mekanik** untuk melacak pengerjaan aktual serta **Dashboard Business Intelligence (BI)** untuk menganalisis performa efisiensi waktu mekanik dan pendapatan bengkel secara berkala.

---

## 🚀 Fitur Utama

Sistem ini mendukung arsitektur multi-role dengan fungsionalitas khusus untuk masing-masing peran:

### 1. 👤 Dashboard Pelanggan (Customer)
*   **Live Queue Status**: Bar informasi interaktif status kepadatan antrean bengkel secara real-time.
*   **Estimasi Waktu Pintar**: Perhitungan otomatis perkiraan jam selesai servis sebelum melakukan pemesanan (dihitung secara kumulatif berdasarkan antrean berjalan dan durasi standar jenis layanan).
*   **Form Booking Cepat**: Memasukkan nama pelanggan, model motor, dan jenis servis secara instan.
*   **Riwayat Booking**: Pelacakan status pengerjaan (Pending, Processing, Completed, Cancelled).

### 2. 🔧 Panel Mekanik
*   **Kanban Alur Kerja Sederhana**: Mekanik dapat melihat daftar motor yang mengantre.
*   **Satu-Klik Progress**: Tombol **"Mulai Kerja"** dan **"Selesai"** untuk merekam waktu kerja secara presisi ke dalam milidetik database.
*   **Quality Check**: Status penyelesaian pengerjaan otomatis dicatat untuk bahan metrik analisis BI.

### 3. 📊 Dashboard Business Intelligence (BI) & Admin
*   **Analisis Efisiensi Waktu**: Perbandingan grafik visual antara **Target Estimasi Durasi** vs **Realisasi Aktual Lapangan** menggunakan **Chart.js**.
*   **Ringkasan Finansial**: Akumulasi pendapatan kotor terestimasi berdasarkan servis yang berhasil diselesaikan.
*   **Skor Efisiensi**: Persentase ketepatan waktu kerja mekanik dibanding durasi standar.
*   **Filter Rentang Tanggal**: Memudahkan analisis performa harian, mingguan, maupun bulanan.
*   **Cetak PDF**: Fitur instan untuk mencetak laporan visual dashboard langsung ke bentuk dokumen fisik/PDF.
*   **Manajemen Layanan**: Admin dapat mengelola jenis servis, harga, dan durasi standar pengerjaan.

---

## 🛠️ Teknologi yang Digunakan

*   **Backend Framework**: [Laravel 11](https://laravel.com)
*   **Frontend Interaktif**: [Livewire Volt](https://livewire.laravel.com/docs/volt) (TALL Stack approach)
*   **Styling & Tema**: [Tailwind CSS](https://tailwindcss.com) (dengan dukungan Dark Mode)
*   **Visualisasi Data**: [Chart.js](https://www.chartjs.org/)
*   **Database**: MySQL / PostgreSQL / SQLite

---

## ⚙️ Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Kloning Repositori
```bash
git clone https://github.com/xazriel/e-bengkel.git
cd e-bengkel
```

### 2. Pasang Dependensi Composer (PHP) & NPM (Javascript)
```bash
composer install
npm install
```

### 3. Duplikat & Atur File Environment
Salin file `.env.example` menjadi `.env` kemudian sesuaikan konfigurasi database Anda.
```bash
cp .env.example .env
```
_Jika menggunakan SQLite (bawaan Laravel 11), pastikan database path sudah terarah dengan benar._

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database & Seeder
Perintah ini akan membuat semua tabel database yang diperlukan dan memasukkan data sampel (akun demo & jenis layanan awal).
```bash
php artisan migrate --seed
```

### 6. Build Aset Frontend & Jalankan Server Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```
Dan jalankan kompilasi aset Vite (di terminal terpisah):
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser Anda di alamat: `http://127.0.0.1:8000`

---

## 🔑 Akun Uji Coba (Demo Credentials)

Anda dapat masuk langsung ke sistem menggunakan akun bawaan hasil seeder berikut:

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@66garage.com` | `admin123` | Mengelola Layanan, Melihat Dashboard BI, Cetak PDF |
| **Mekanik** | `mekanik@66garage.com` | `mekanik123` | Mengubah status servis (Mulai & Selesai) |
| **Pelanggan** | *(Silakan daftar akun baru di halaman Register)* | | Melakukan booking servis & melihat estimasi waktu |

---

## 🗄️ Skema Database Utama

### 1. Tabel `services`
Menyimpan katalog servis motor:
*   `id` (Primary Key)
*   `name` (Nama Layanan - e.g., Ganti Oli, Servis CVT)
*   `estimated_duration` (Estimasi durasi standar dalam satuan MENIT)
*   `price` (Harga Jasa Servis)

### 2. Tabel `bookings`
Melacak alur pemesanan dan metrik waktu:
*   `id` (Primary Key)
*   `user_id` (Relasi ke tabel `users`)
*   `service_id` (Relasi ke tabel `services`)
*   `customer_name` (Nama pemilik motor)
*   `motor_model` (Model/Merk Motor)
*   `booking_time` (Waktu reservasi dibuat)
*   `estimated_finish_at` (Target jam selesai yang diprediksi sistem)
*   `actual_start_at` (Waktu pengerjaan dimulai oleh mekanik - nullable)
*   `actual_finish_at` (Waktu pengerjaan selesai oleh mekanik - nullable)
*   `status` (Enum: `pending`, `processing`, `completed`, `cancelled`)
