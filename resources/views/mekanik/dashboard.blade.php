<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🔧 Panel Mekanik — 66 Garage
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                MEKANIK
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white text-gray-800">📋 Input Booking Pelanggan</h3>
                    <livewire:booking-form />
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white text-gray-800">⚙️ Antrian Servis</h3>
                    <livewire:mekanik-panel />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
