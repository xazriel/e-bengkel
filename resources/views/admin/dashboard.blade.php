<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🔧 Dashboard Admin — 66 Garage
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                ADMIN
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Error --}}
            @if (session('error'))
                <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- BI Dashboard --}}
            <livewire:bi-dashboard />

            {{-- Booking + Mekanik Panel --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white text-gray-800">📋 Input Booking</h3>
                    <livewire:booking-form />
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white text-gray-800">🔧 Panel Mekanik</h3>
                    <livewire:mekanik-panel />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
