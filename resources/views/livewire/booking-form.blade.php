<?php

use Livewire\Volt\Component;
use App\Models\Service;
use App\Models\Booking;
use Carbon\Carbon;

new class extends Component {
    public $services;
    public $selectedService = '';
    public $customerName = '';
    public $motorModel = '';
    public $estimatedTime = null;
    public $selectedServiceName = '';
    public $selectedServicePrice = null;
    public $queueCount = 0;
    public bool $isSubmitting = false;
    public bool $showSuccess = false;
    public ?string $successMessage = null;
    public ?string $successTime = null;

    public function mount()
    {
        $this->services = Service::orderBy('name')->get();
        // Pre-fill nama dari user yang login
        $this->customerName = auth()->user()->name ?? '';
        $this->refreshQueue();
    }

    public function refreshQueue(): void
    {
        $this->queueCount = Booking::whereIn('status', ['pending', 'processing'])->count();
    }

    // Otomatis jalan tiap $selectedService berubah
    public function updatedSelectedService($value): void
    {
        if (!$value) {
            $this->estimatedTime = null;
            $this->selectedServiceName = '';
            $this->selectedServicePrice = null;
            return;
        }

        $service = Service::find($value);
        if (!$service) return;

        $this->selectedServiceName = $service->name;
        $this->selectedServicePrice = $service->price;

        // Ambil booking terakhir yang masih dalam antrean
        $lastBooking = Booking::whereIn('status', ['pending', 'processing'])
            ->orderBy('estimated_finish_at', 'desc')
            ->first();

        $startTime = $lastBooking
            ? Carbon::parse($lastBooking->estimated_finish_at)
            : now();

        $this->estimatedTime = $startTime->addMinutes($service->estimated_duration)->format('H:i');
        $this->refreshQueue();
    }

    public function save(): void
    {
        $this->isSubmitting = true;

        $this->validate([
            'selectedService' => 'required',
            'customerName'    => 'required|min:3',
            'motorModel'      => 'required|min:2',
        ], [
            'selectedService.required' => 'Pilih jenis layanan terlebih dahulu.',
            'customerName.required'    => 'Nama pemilik wajib diisi.',
            'customerName.min'         => 'Nama minimal 3 karakter.',
            'motorModel.required'      => 'Model motor wajib diisi.',
            'motorModel.min'           => 'Nama model motor terlalu pendek.',
        ]);

        $service = Service::findOrFail($this->selectedService);

        // Hitung ulang estimasi sebelum simpan
        $lastBooking = Booking::whereIn('status', ['pending', 'processing'])
            ->orderBy('estimated_finish_at', 'desc')
            ->first();

        $startTime  = $lastBooking ? Carbon::parse($lastBooking->estimated_finish_at) : now();
        $finishTime = $startTime->copy()->addMinutes($service->estimated_duration);

        Booking::create([
            'user_id'             => auth()->id(),
            'service_id'          => $this->selectedService,
            'customer_name'       => $this->customerName,
            'motor_model'         => $this->motorModel,
            'booking_time'        => now(),
            'estimated_finish_at' => $finishTime,
            'status'              => 'pending',
        ]);

        $this->successMessage = "Booking berhasil! Motor <strong>{$this->motorModel}</strong> untuk layanan <strong>{$service->name}</strong> sudah masuk antrean.";
        $this->successTime    = $finishTime->format('H:i');
        $this->showSuccess    = true;

        $this->reset(['selectedService', 'motorModel', 'estimatedTime', 'selectedServiceName', 'selectedServicePrice']);
        $this->customerName  = auth()->user()->name ?? '';
        $this->isSubmitting  = false;
        $this->refreshQueue();
    }

    public function resetSuccess(): void
    {
        $this->showSuccess = false;
        $this->successMessage = null;
        $this->successTime = null;
    }
}; ?>

<div class="space-y-5">

    {{-- === SUCCESS STATE === --}}
    @if ($showSuccess)
        <div class="relative overflow-hidden bg-gradient-to-br from-green-900/60 to-emerald-900/40 border border-green-600/40 rounded-2xl p-6 text-center">
            {{-- Decorative glow --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-48 h-48 bg-green-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                {{-- Checkmark icon --}}
                <div class="w-16 h-16 bg-green-500/20 border-2 border-green-500/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h3 class="text-xl font-black text-white mb-2">Booking Berhasil! 🎉</h3>
                <p class="text-sm text-gray-300 mb-5 leading-relaxed">{!! $successMessage !!}</p>

                {{-- Estimated time card --}}
                <div class="inline-block bg-black/30 border border-green-500/30 rounded-xl px-6 py-4 mb-5">
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Estimasi Selesai</p>
                    <p class="text-4xl font-black text-green-400 tabular-nums">{{ $successTime }}
                        <span class="text-lg font-medium text-gray-400">WIB</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">*berdasarkan antrean saat ini</p>
                </div>

                <div>
                    <button
                        wire:click="resetSuccess"
                        class="px-6 py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 text-white font-semibold text-sm rounded-xl transition">
                        + Booking Lagi
                    </button>
                </div>
            </div>
        </div>
    @else

    {{-- === QUEUE STATUS BAR === --}}
    <div class="flex items-center gap-3 bg-gray-800/50 border border-white/5 rounded-xl px-4 py-3">
        <div class="flex-shrink-0">
            @if ($queueCount === 0)
                <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
            @elseif ($queueCount <= 3)
                <div class="w-2.5 h-2.5 bg-yellow-400 rounded-full animate-pulse"></div>
            @else
                <div class="w-2.5 h-2.5 bg-red-400 rounded-full animate-pulse"></div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            @if ($queueCount === 0)
                <p class="text-sm font-semibold text-green-400">Bengkel sedang kosong — langsung dikerjakan!</p>
            @elseif ($queueCount === 1)
                <p class="text-sm font-semibold text-yellow-300">Ada <strong>1 motor</strong> dalam antrean.</p>
            @else
                <p class="text-sm font-semibold text-orange-300">Ada <strong>{{ $queueCount }} motor</strong> dalam antrean saat ini.</p>
            @endif
        </div>
        <button wire:click="refreshQueue" class="text-gray-500 hover:text-gray-300 transition flex-shrink-0" title="Refresh antrean">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>

    {{-- === BOOKING FORM === --}}
    <form wire:submit="save" class="space-y-4">

        {{-- Nama Pemilik --}}
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">
                Nama Pemilik Motor
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model="customerName"
                    placeholder="Nama lengkap pemilik"
                    class="w-full pl-9 pr-4 py-2.5 bg-gray-800 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600
                           focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition">
            </div>
            @error('customerName')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Model Motor --}}
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">
                Model Motor
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5.5M9 7h1m6 0h1M13 6l3 2.5M3 14h1"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model="motorModel"
                    placeholder="Contoh: Honda Vario 160, Yamaha NMAX"
                    class="w-full pl-9 pr-4 py-2.5 bg-gray-800 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600
                           focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition">
            </div>
            @error('motorModel')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Jenis Layanan --}}
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">
                Jenis Layanan
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <select
                    wire:model.live="selectedService"
                    class="w-full pl-9 pr-8 py-2.5 bg-gray-800 border border-white/10 rounded-xl text-sm text-white
                           focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition appearance-none">
                    <option value="">-- Pilih layanan --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }} · {{ $service->estimated_duration }} menit · Rp {{ number_format($service->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            @error('selectedService')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Preview Layanan + Estimasi --}}
        @if ($selectedService && $estimatedTime)
            <div class="grid grid-cols-2 gap-3">
                {{-- Harga --}}
                <div class="bg-gray-800/60 border border-white/5 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Biaya Servis</p>
                    <p class="text-lg font-black text-green-400">
                        Rp {{ number_format($selectedServicePrice, 0, ',', '.') }}
                    </p>
                </div>
                {{-- Estimasi Selesai --}}
                <div class="bg-red-950/40 border border-red-500/20 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Estimasi Selesai</p>
                    <p class="text-lg font-black text-red-400 tabular-nums">
                        {{ $estimatedTime }} <span class="text-xs font-normal text-gray-500">WIB</span>
                    </p>
                </div>
            </div>
        @endif

        {{-- Submit Button --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-60 cursor-not-allowed"
            class="w-full relative overflow-hidden py-3.5 px-6 bg-red-600 hover:bg-red-700 disabled:bg-red-800
                   text-white font-black text-sm rounded-xl transition duration-200
                   shadow-lg shadow-red-900/30 hover:shadow-red-900/50">
            <span wire:loading.remove wire:target="save" class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                KONFIRMASI BOOKING
            </span>
            <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Memproses...
            </span>
        </button>

    </form>
    @endif
</div>