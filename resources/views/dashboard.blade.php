<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Bengkel & BI Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <livewire:bi-dashboard />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Input Booking</h3>
                    <livewire:booking-form />
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 dark:text-white">Panel Mekanik</h3>
                    <livewire:mekanik-panel />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>