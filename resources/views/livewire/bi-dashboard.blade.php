<?php

use Livewire\Volt\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component {
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Set default filter: 30 hari terakhir
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function with()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Query dasar dengan filter tanggal
        $baseQuery = Booking::where('status', 'completed')
            ->whereBetween('actual_finish_at', [$start, $end]);

        // 1. Data untuk Widget
        $totalMotor = (clone $baseQuery)->count();
        $totalRevenue = (clone $baseQuery)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');
        
        $onTime = (clone $baseQuery)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->whereRaw('TIMESTAMPDIFF(MINUTE, actual_start_at, actual_finish_at) <= services.estimated_duration')
            ->count();
        
        $efficiencyRate = $totalMotor > 0 ? round(($onTime / $totalMotor) * 100) : 0;

        // 2. Data untuk Grafik
        $stats = DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->where('status', 'completed')
            ->whereBetween('actual_finish_at', [$start, $end])
            ->select(
                'services.name',
                DB::raw('AVG(services.estimated_duration) as target'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, actual_start_at, actual_finish_at)) as aktual')
            )
            ->groupBy('services.name')
            ->get();

        return [
            'totalMotor' => $totalMotor,
            'totalRevenue' => number_format($totalRevenue, 0, ',', '.'),
            'efficiencyRate' => $efficiencyRate,
            'labels' => $stats->pluck('name'),
            'targets' => $stats->pluck('target'),
            'actuals' => $stats->pluck('aktual'),
        ];
    }
}; ?>

<div>
    <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg items-end">
        <div class="flex gap-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm">
            </div>
        </div>
        <div class="flex-1 text-right">
            <button onclick="window.print()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-bold text-sm transition flex items-center gap-2 ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                CETAK LAPORAN (PDF)
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-80 uppercase font-bold">Total Motor Selesai</p>
            <h2 class="text-4xl font-black">{{ $totalMotor }}</h2>
            <p class="text-xs mt-2">Periode terpilih</p>
        </div>

        <div class="bg-green-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-80 uppercase font-bold">Estimasi Pendapatan</p>
            <h2 class="text-4xl font-black">Rp {{ $totalRevenue }}</h2>
            <p class="text-xs mt-2 text-green-200">Berdasarkan servis selesai</p>
        </div>

        <div class="bg-purple-600 p-6 rounded-xl shadow-lg text-white">
            <p class="text-sm opacity-80 uppercase font-bold">Skor Efisiensi</p>
            <h2 class="text-4xl font-black">{{ $efficiencyRate }}%</h2>
            <p class="text-xs mt-2 text-purple-200">Ketepatan waktu mekanik</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h3 class="text-xl font-bold mb-6 dark:text-white text-gray-800">Analisis Performa Waktu (Menit)</h3>
        <div class="h-64">
            <canvas id="biChart" wire:ignore></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let myChart;

        function initChart() {
            const ctx = document.getElementById('biChart');
            if (!ctx) return;

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Target Estimasi',
                        data: @json($targets),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }, {
                        label: 'Realisasi Lapangan',
                        data: @json($actuals),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Jalankan saat pertama kali load
        document.addEventListener('livewire:navigated', initChart);
        
        // Jalankan ulang setiap kali Livewire mengupdate data (untuk filter tanggal)
        document.addEventListener('livewire:load', initChart);
        
        // Hook khusus Volt agar chart update setelah filter
        window.addEventListener('contentChanged', initChart);
    </script>
</div>