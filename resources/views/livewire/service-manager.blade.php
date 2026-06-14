<?php

use Livewire\Volt\Component;
use App\Models\Service;

new class extends Component {
    // --- State ---
    public bool $showForm = false;
    public ?int $editingId = null;

    // --- Form Fields ---
    public string $name = '';
    public string $estimated_duration = '';
    public string $price = '';

    // --- Computed Data ---
    public function with(): array
    {
        return [
            'services' => Service::orderBy('name')->get(),
        ];
    }

    // --- Open form untuk tambah baru ---
    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showForm = true;
    }

    // --- Open form untuk edit ---
    public function openEdit(int $id): void
    {
        $service = Service::findOrFail($id);
        $this->editingId = $id;
        $this->name = $service->name;
        $this->estimated_duration = (string) $service->estimated_duration;
        $this->price = (string) $service->price;
        $this->showForm = true;
    }

    // --- Simpan (tambah atau edit) ---
    public function save(): void
    {
        $this->validate([
            'name'               => 'required|min:3|max:100',
            'estimated_duration' => 'required|integer|min:1|max:999',
            'price'              => 'required|numeric|min:1000',
        ], [
            'name.required'               => 'Nama layanan wajib diisi.',
            'estimated_duration.required' => 'Durasi estimasi wajib diisi.',
            'estimated_duration.integer'  => 'Durasi harus berupa angka menit.',
            'estimated_duration.min'      => 'Durasi minimal 1 menit.',
            'price.required'              => 'Harga wajib diisi.',
            'price.min'                   => 'Harga minimal Rp 1.000.',
        ]);

        Service::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'               => $this->name,
                'estimated_duration' => (int) $this->estimated_duration,
                'price'              => (float) $this->price,
            ]
        );

        session()->flash('success', $this->editingId
            ? "Layanan \"{$this->name}\" berhasil diperbarui."
            : "Layanan \"{$this->name}\" berhasil ditambahkan."
        );

        $this->resetForm();
        $this->showForm = false;
        $this->editingId = null;
    }

    // --- Hapus layanan ---
    public function delete(int $id): void
    {
        $service = Service::find($id);
        if ($service) {
            $name = $service->name;
            $service->delete();
            session()->flash('success', "Layanan \"{$name}\" berhasil dihapus.");
        }
    }

    // --- Tutup form ---
    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->editingId = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->estimated_duration = '';
        $this->price = '';
        $this->resetValidation();
    }
}; ?>

<div class="space-y-6">

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-xl">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Layanan Bengkel</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola harga dan estimasi durasi tiap layanan</p>
            </div>
            @if (!$showForm)
                <button
                    wire:click="openCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Layanan
                </button>
            @endif
        </div>

        {{-- Form Tambah/Edit --}}
        @if ($showForm)
            <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">
                    {{ $editingId ? '✏️ Edit Layanan' : '➕ Tambah Layanan Baru' }}
                </h4>
                <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Nama --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase">Nama Layanan</label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Contoh: Ganti Oli & Cek Rutin"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase">Estimasi Durasi (Menit)</label>
                        <input
                            type="number"
                            wire:model="estimated_duration"
                            placeholder="30"
                            min="1"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        @error('estimated_duration') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase">Harga (Rp)</label>
                        <input
                            type="number"
                            wire:model="price"
                            placeholder="50000"
                            min="0"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        @error('price') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-200">
                            {{ $editingId ? 'Simpan Perubahan' : 'Tambahkan' }}
                        </button>
                        <button type="button" wire:click="cancelForm"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-lg text-sm transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Tabel Layanan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Layanan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $service->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                                    ⏱ {{ $service->estimated_duration }} Menit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        wire:click="openEdit({{ $service->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-lg transition">
                                        ✏️ Edit
                                    </button>
                                    <button
                                        wire:click="delete({{ $service->id }})"
                                        wire:confirm="Yakin hapus layanan '{{ $service->name }}'? Booking yang sudah ada tidak akan terpengaruh."
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/60 rounded-lg transition">
                                        🗑 Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 text-sm italic">
                                Belum ada layanan. Klik "Tambah Layanan" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Summary --}}
        @if ($services->count() > 0)
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                Total: <strong class="text-gray-700 dark:text-gray-300">{{ $services->count() }} layanan</strong>
            </div>
        @endif
    </div>
</div>
