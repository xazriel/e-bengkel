<?php

use Livewire\Volt\Component;
use App\Models\Booking;

new class extends Component {
    // Fungsi untuk mengambil data antrean secara real-time
    public function with()
    {
        return [
            'bookings' => Booking::whereIn('status', ['pending', 'processing'])
                ->with('service')
                ->orderBy('booking_time', 'asc')
                ->get(),
        ];
    }

    public function startService($id)
    {
        Booking::find($id)->update([
            'status' => 'processing',
            'actual_start_at' => now(),
        ]);
    }

    public function finishService($id)
    {
        Booking::find($id)->update([
            'status' => 'completed',
            'actual_finish_at' => now(),
        ]);

        session()->flash('status', 'Kerja bagus! Data durasi telah dicatat untuk laporan BI.');
    }
}; ?>

<div class="space-y-4">
    @if (session('status'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @forelse($bookings as $booking)
            <div class="p-5 bg-white dark:bg-gray-800 shadow rounded-xl border-l-4 {{ $booking->status == 'processing' ? 'border-yellow-500' : 'border-blue-500' }}">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">{{ $booking->motor_model }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Pelanggan: {{ $booking->customer_name }}</p>
                        <p class="text-blue-600 font-semibold text-xs mt-1 uppercase">{{ $booking->service->name }}</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Target Selesai:</p>
                        <p class="font-mono font-bold dark:text-white">{{ \Carbon\Carbon::parse($booking->estimated_finish_at)->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    @if($booking->status == 'pending')
                        <button wire:click="startService({{ $booking->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">
                            MULAI KERJA
                        </button>
                    @else
                        <button wire:click="finishService({{ $booking->id }})" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition">
                            SELESAI (CEK KUALITAS)
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-gray-100 dark:bg-gray-700 rounded-xl italic text-gray-500">
                Belum ada antrean motor masuk.
            </div>
        @endforelse
    </div>
</div>