<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gray-900 border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 group">
                        <div class="w-8 h-8 bg-red-600 group-hover:bg-red-500 rounded-lg flex items-center justify-center font-black text-white text-sm transition">
                            66
                        </div>
                        <span class="font-black text-base text-white tracking-tight">
                            66 <span class="text-red-500">Garage</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-1 sm:ms-10 sm:flex sm:items-center">

                    {{-- Dashboard link: sesuaikan tujuan berdasarkan role --}}
                    @php
                        $dashRoute = match(auth()->user()->role) {
                            'admin'   => 'admin.dashboard',
                            'mekanik' => 'mekanik.dashboard',
                            default   => 'user.dashboard',
                        };
                        $dashActive = request()->routeIs('admin.dashboard')
                                   || request()->routeIs('mekanik.dashboard')
                                   || request()->routeIs('user.dashboard');
                    @endphp

                    <a href="{{ route($dashRoute) }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition
                              {{ $dashActive ? 'bg-red-600/20 text-red-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       wire:navigate>
                        Dashboard
                    </a>

                    {{-- Admin only: Kelola Layanan --}}
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.services') }}"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition
                                  {{ request()->routeIs('admin.services') ? 'bg-red-600/20 text-red-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                           wire:navigate>
                            🛠 Kelola Layanan
                        </a>
                    @endif

                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- Role Badge --}}
                @php
                    $badgeClass = match(auth()->user()->role) {
                        'admin'   => 'bg-red-900/50 text-red-400 border-red-800/50',
                        'mekanik' => 'bg-blue-900/50 text-blue-400 border-blue-800/50',
                        default   => 'bg-gray-800 text-gray-400 border-gray-700',
                    };
                    $badgeLabel = match(auth()->user()->role) {
                        'admin'   => '⭐ Admin',
                        'mekanik' => '🔧 Mekanik',
                        default   => '🏍 Pelanggan',
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                    {{ $badgeLabel }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/10 text-sm leading-4 font-medium rounded-lg text-gray-300 bg-white/5 hover:bg-white/10 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                 x-text="name"
                                 x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="/">
                            🏠 Halaman Utama
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route($dashRoute) }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition"
               wire:navigate>
                Dashboard
            </a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.services') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition"
                   wire:navigate>
                    🛠 Kelola Layanan
                </a>
            @endif
        </div>

        <!-- Responsive User Info -->
        <div class="pt-4 pb-3 border-t border-white/5 px-4">
            <div class="flex items-center gap-3 mb-3">
                <div>
                    <div class="font-semibold text-sm text-white"
                         x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                         x-text="name"
                         x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                </div>
                <span class="ms-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                    {{ $badgeLabel }}
                </span>
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile') }}"
                   class="block px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition"
                   wire:navigate>
                    Profile
                </a>
                <a href="/"
                   class="block px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-white/5 hover:text-white transition">
                    🏠 Halaman Utama
                </a>
                <button wire:click="logout" class="w-full text-start">
                    <span class="block px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-900/20 transition">
                        Log Out
                    </span>
                </button>
            </div>
        </div>
    </div>
</nav>
