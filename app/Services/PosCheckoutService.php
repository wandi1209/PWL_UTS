<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosCheckoutService
{
    public function checkout(int $userId, string $pembeli, array $cart): Penjualan
    {
        if (count($cart) === 0) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        return DB::transaction(function () use ($userId, $pembeli, $cart) {
            $penjualan = Penjualan::create([
                'user_id' => $userId,
                'pembeli' => $pembeli,
                'penjualan_kode' => $this->generateKode(),
                'penjualan_tanggal' => now(),
            ]);

            foreach ($cart as $item) {
                $barangId = (int) ($item['barang_id'] ?? 0);
                $qty = (int) ($item['qty'] ?? 0);

                if ($barangId <= 0 || $qty <= 0) {
                    throw ValidationException::withMessages([
                        'cart' => 'Data item keranjang tidak valid.',
                    ]);
                }

                $barang = Barang::query()
                    ->whereKey($barangId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stokTersedia = $this->getAvailableStock($barang->barang_id);

                if ($qty > $stokTersedia) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok tidak cukup untuk barang: ' . $barang->barang_nama,
                    ]);
                }

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang->barang_id,
                    'jumlah' => $qty,
                    'harga' => (int) $barang->harga_jual,
                ]);
            }

            return $penjualan->load(['user', 'details.barang']);
        });
    }

    public function getAvailableStock(int $barangId): int
    {
        $stokMasuk = (int) Stok::query()
            ->where('barang_id', $barangId)
            ->sum('stok_jumlah');

        $stokKeluar = (int) PenjualanDetail::query()
            ->where('barang_id', $barangId)
            ->sum('jumlah');

        return max(0, $stokMasuk - $stokKeluar);
    }

    protected function generateKode(): string
    {
        do {
            $kode = 'PJL-' . now()->format('mdHi') . '-' . str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
        } while (Penjualan::query()->where('penjualan_kode', $kode)->exists());

        return $kode;
    }
}