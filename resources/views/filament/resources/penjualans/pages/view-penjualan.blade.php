<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white/95 p-6 shadow-sm">
            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Kode Transaksi</p>
                    <p class="mt-2 text-lg font-black text-gray-900">{{ $record->penjualan_kode }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $record->penjualan_tanggal->format('d-m-Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pembeli</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $record->pembeli }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Kasir</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $record->user->nama }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white/95 shadow-sm">
            <div class="p-6">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Item Transaksi</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b-2 border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Barang</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Kode</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Harga Satuan</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($record->details as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $detail->barang->barang_nama }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $detail->barang->barang_kode }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Tidak ada item dalam transaksi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="overflow-hidden rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-6 shadow-sm">
            <div class="grid grid-cols-3 gap-6">
                <div class="rounded-xl border border-amber-200 bg-white/80 px-4 py-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Item</p>
                    <p class="mt-2 text-2xl font-black text-gray-900">{{ $record->details->count() }}</p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-white/80 px-4 py-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Qty</p>
                    <p class="mt-2 text-2xl font-black text-gray-900">{{ $record->details->sum('jumlah') }}</p>
                </div>
                <div class="rounded-xl border border-amber-300 bg-white px-4 py-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-700">Total Harga</p>
                    <p class="mt-2 text-2xl font-black text-amber-600">Rp {{ number_format($record->details->sum(fn ($d) => $d->harga * $d->jumlah), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center text-xs text-gray-500">
            <p>Dibuat: {{ $record->created_at->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</x-filament-panels::page>
