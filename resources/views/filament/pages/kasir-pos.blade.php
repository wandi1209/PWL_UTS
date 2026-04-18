<x-filament-panels::page>
    @php
        $cartItems = count($cart);
        $cartQty = (int) collect($cart)->sum('qty');
    @endphp

    <div class="relative overflow-hidden rounded-3xl border border-amber-200/70 bg-gradient-to-br from-amber-50 via-orange-50 to-white p-5 sm:p-6">
        <div class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full bg-amber-300/25 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-10 -left-10 h-44 w-44 rounded-full bg-orange-300/20 blur-2xl"></div>

        <div class="relative mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">POS Kasir</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900">Transaksi Cepat & Akurat</h2>
                <p class="mt-1 text-sm text-gray-600">Pilih barang di kiri, cek keranjang dan checkout di kanan.</p>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <div class="rounded-2xl border border-amber-200 bg-white/90 px-4 py-3 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Item</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cartItems }}</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-white/90 px-4 py-3 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Qty</p>
                    <p class="text-xl font-bold text-gray-900">{{ $cartQty }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <section class="space-y-4 xl:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white/95 p-5 shadow-sm sm:p-6">
                    <label class="mb-3 block text-base font-semibold text-gray-700">Cari Barang</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-2xl">🔎</span>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            class="w-full rounded-2xl border-2 border-gray-200 bg-gray-50 px-5 py-4 pl-16 text-base placeholder:text-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                            placeholder="Cari kode atau nama barang..."
                        />
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white/95 p-5 shadow-sm sm:p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-bold uppercase tracking-wide text-gray-700">Daftar Barang</h3>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            {{ $this->barangs->count() }} ditampilkan
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-3">
                        @forelse($this->barangs as $barang)
                            <button
                                type="button"
                                wire:click="addItem({{ $barang['barang_id'] }})"
                                class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-150 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg"
                            >
                                <!-- Foto Barang -->
                                <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                    @php
                                        $barangModel = \App\Models\Barang::find($barang['barang_id']);
                                        $fotoPath = $barangModel?->foto ?? null;
                                    @endphp
                                    @if($fotoPath)
                                        <img 
                                            src="{{ \Illuminate\Support\Facades\Storage::url($fotoPath) }}" 
                                            alt="{{ $barang['barang_nama'] }}" 
                                            class="h-full w-full object-cover transition group-hover:scale-110"
                                        />
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <svg class="h-20 w-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0l9.172 9.172m0 0l-3-3m3 3V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12" />
                                            </svg>
                                        </div>
                                    @endif
                                    <!-- Stock Badge -->
                                    <div class="absolute right-3 top-3">
                                        <span class="inline-block rounded-full px-3 py-1.5 text-xs font-bold {{ $barang['stok'] > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                            {{ $barang['stok'] > 0 ? 'Ready' : 'Habis' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Info Produk -->
                                <div class="p-4">
                                    <p class="line-clamp-2 text-base font-bold text-gray-900">{{ $barang['barang_nama'] }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-500">{{ $barang['barang_kode'] }}</p>

                                    <div class="mt-4 flex items-end justify-between border-t border-gray-200 pt-3">
                                        <div>
                                            <p class="text-xs text-gray-500">Harga</p>
                                            <p class="text-lg font-black text-gray-900">Rp {{ number_format($barang['harga_jual'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Stok</p>
                                            <p class="text-lg font-bold text-amber-600">{{ $barang['stok'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm text-gray-500">
                                Barang tidak ditemukan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <aside class="xl:sticky xl:top-6 xl:h-fit">
                <div class="space-y-4 rounded-2xl border border-gray-200 bg-white/95 p-5 shadow-sm sm:p-6">
                    <div>
                        <label class="mb-3 block text-base font-semibold text-gray-700">Nama Pembeli</label>
                        <input
                            type="text"
                            wire:model.live="pembeli"
                            class="w-full rounded-2xl border-2 border-gray-200 bg-gray-50 px-5 py-4 text-base focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                            maxlength="50"
                            placeholder="Contoh: Umum"
                        />
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Keranjang</h3>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">{{ $cartQty }} qty</span>
                    </div>

                    <div class="max-h-[360px] space-y-3 overflow-auto pr-1">
                        @forelse($cart as $item)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="line-clamp-2 text-sm font-semibold text-gray-900">{{ $item['barang_nama'] }}</p>
                                    <button
                                        type="button"
                                        wire:click="removeItem({{ $item['barang_id'] }})"
                                        class="rounded-lg border border-rose-200 px-2 py-1 text-xs font-semibold text-rose-700 transition hover:bg-rose-50"
                                    >
                                        Hapus
                                    </button>
                                </div>

                                <div class="mt-1 text-xs text-gray-600">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>

                                <div class="mt-3 flex items-center justify-between">
                                    <div class="inline-flex items-center overflow-hidden rounded-lg border border-gray-300 bg-white">
                                        <button
                                            type="button"
                                            wire:click="decrementQty({{ $item['barang_id'] }})"
                                            class="px-3 py-1.5 text-sm font-bold text-gray-700 transition hover:bg-gray-100"
                                        >
                                            -
                                        </button>
                                        <span class="min-w-10 border-x border-gray-300 px-2 text-center text-sm font-bold text-gray-900">
                                            {{ $item['qty'] }}
                                        </span>
                                        <button
                                            type="button"
                                            wire:click="incrementQty({{ $item['barang_id'] }})"
                                            class="px-3 py-1.5 text-sm font-bold text-gray-700 transition hover:bg-gray-100"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Subtotal</p>
                                        <p class="text-sm font-extrabold text-gray-900">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500">
                                Keranjang kosong.
                            </div>
                        @endforelse
                    </div>

                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Bayar</span>
                            <span class="text-2xl font-black tracking-tight text-gray-900">
                                Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <button
                            type="button"
                            wire:click="checkout"
                            class="w-full rounded-xl bg-amber-500 py-3 text-sm font-extrabold uppercase tracking-wide text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2"
                        >
                            Checkout Sekarang
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-filament-panels::page>