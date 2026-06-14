<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-xl text-white leading-tight">
                    Booking Servis Motor
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">66 Garage — Sistem Antrean Digital</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-800 text-gray-400 border border-white/10">
                🏍 PELANGGAN
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- === WELCOME BANNER === --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-gray-900 to-gray-800 border border-white/5 rounded-2xl px-6 py-7">
                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-52 h-52 bg-red-600/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-red-900/10 rounded-full blur-2xl"></div>
                </div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-red-400 uppercase tracking-widest mb-1">Selamat datang 👋</p>
                        <h1 class="text-2xl font-black text-white mb-1">{{ auth()->user()->name }}</h1>
                        <p class="text-sm text-gray-400">
                            @if ($pendingCount > 0)
                                Kamu punya <strong class="text-yellow-400">{{ $pendingCount }} booking aktif</strong> yang sedang diproses.
                            @else
                                Belum ada booking aktif. Yuk booking servis motormu sekarang!
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-14 h-14 bg-red-600/20 border border-red-500/30 rounded-xl flex items-center justify-center text-3xl">
                        🏍️
                    </div>
                </div>
            </div>

            {{-- === MAIN GRID === --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- === KIRI: BOOKING FORM === --}}
                <div class="lg:col-span-3">
                    <div class="bg-gray-900 border border-white/5 rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/5">
                            <h2 class="font-black text-white text-lg flex items-center gap-2">
                                <span class="w-7 h-7 bg-red-600/20 border border-red-500/30 rounded-lg flex items-center justify-center text-sm">📋</span>
                                Booking Servis Baru
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Isi formulir di bawah untuk mendaftarkan motor kamu</p>
                        </div>
                        <div class="p-6">
                            <livewire:booking-form />
                        </div>
                    </div>
                </div>

                {{-- === KANAN: RIWAYAT + INFO === --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Riwayat Booking --}}
                    <div class="bg-gray-900 border border-white/5 rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                            <h2 class="font-black text-white text-base flex items-center gap-2">
                                <span class="w-7 h-7 bg-blue-600/20 border border-blue-500/30 rounded-lg flex items-center justify-center text-sm">📜</span>
                                Riwayat Booking
                            </h2>
                            <span class="text-xs text-gray-600">5 terbaru</span>
                        </div>

                        <div class="divide-y divide-white/5">
                            @forelse ($myBookings as $booking)
                                <div class="px-5 py-4 hover:bg-white/[0.02] transition">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-white truncate">{{ $booking->motor_model }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $booking->service->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @php
                                                $statusConfig = [
                                                    'pending'    => ['bg-yellow-900/50 text-yellow-400 border-yellow-700/50', '⏳ Menunggu'],
                                                    'processing' => ['bg-blue-900/50 text-blue-400 border-blue-700/50', '🔧 Dikerjakan'],
                                                    'completed'  => ['bg-green-900/50 text-green-400 border-green-700/50', '✅ Selesai'],
                                                    'cancelled'  => ['bg-red-900/50 text-red-400 border-red-700/50', '❌ Batal'],
                                                ];
                                                $cfg = $statusConfig[$booking->status] ?? ['bg-gray-800 text-gray-400 border-gray-700', $booking->status];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $cfg[0] }}">
                                                {{ $cfg[1] }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Progress bar untuk booking aktif --}}
                                    @if (in_array($booking->status, ['pending', 'processing']))
                                        <div class="mt-3">
                                            <div class="flex justify-between text-[10px] text-gray-600 mb-1">
                                                <span>Estimasi selesai</span>
                                                <span class="font-mono font-bold text-gray-400">
                                                    {{ \Carbon\Carbon::parse($booking->estimated_finish_at)->format('H:i') }} WIB
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-800 rounded-full h-1.5">
                                                @php
                                                    $start    = \Carbon\Carbon::parse($booking->booking_time);
                                                    $end      = \Carbon\Carbon::parse($booking->estimated_finish_at);
                                                    $total    = $end->diffInMinutes($start) ?: 1;
                                                    $elapsed  = min(now()->diffInMinutes($start), $total);
                                                    $progress = round(($elapsed / $total) * 100);
                                                @endphp
                                                <div class="h-1.5 rounded-full transition-all duration-500
                                                    {{ $booking->status === 'processing' ? 'bg-blue-500' : 'bg-yellow-500' }}"
                                                    style="width: {{ $progress }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="px-5 py-10 text-center">
                                    <div class="text-3xl mb-3">📭</div>
                                    <p class="text-sm text-gray-500">Belum ada riwayat booking.</p>
                                    <p class="text-xs text-gray-600 mt-1">Booking pertamamu akan muncul di sini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tips Card --}}
                    <div class="bg-gray-900 border border-white/5 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                            <span>💡</span> Tips Booking
                        </h3>
                        <ul class="space-y-2.5 text-xs text-gray-500">
                            <li class="flex items-start gap-2">
                                <span class="text-red-400 mt-0.5 shrink-0">→</span>
                                Datang sebelum estimasi waktu agar motor langsung dikerjakan.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-red-400 mt-0.5 shrink-0">→</span>
                                Estimasi dihitung berdasarkan antrean saat ini dan bisa berubah.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-red-400 mt-0.5 shrink-0">→</span>
                                Hubungi kami di <strong class="text-gray-400">(021) 6666-6666</strong> untuk info lebih lanjut.
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
