<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Filament\Resources\Penjualans\PenjualanResource;
use App\Models\Barang;
use App\Services\PosCheckoutService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class KasirPos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Kasir POS';
    protected static ?string $title = 'Kasir POS';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.kasir-pos';

    public string $search = '';
    public string $pembeli = 'Umum';
    public array $cart = [];

    public function getBarangsProperty(): Collection
    {
        return Barang::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('barang_nama', 'like', '%' . $this->search . '%')
                      ->orWhere('barang_kode', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('barang_nama')
            ->limit(30)
            ->get()
            ->map(function ($barang) {
                return [
                    'barang_id' => $barang->barang_id,
                    'barang_kode' => $barang->barang_kode,
                    'barang_nama' => $barang->barang_nama,
                    'harga_jual' => (int) $barang->harga_jual,
                    'stok' => app(PosCheckoutService::class)->getAvailableStock((int) $barang->barang_id),
                ];
            });
    }

    public function addItem(int $barangId): void
    {
        $barang = Barang::query()->findOrFail($barangId);
        $stok = app(PosCheckoutService::class)->getAvailableStock((int) $barangId);

        if ($stok <= 0) {
            Notification::make()->title('Stok habis')->danger()->send();
            return;
        }

        foreach ($this->cart as &$item) {
            if ((int) $item['barang_id'] === $barangId) {
                if ((int) $item['qty'] + 1 > $stok) {
                    Notification::make()->title('Qty melebihi stok')->warning()->send();
                    return;
                }

                $item['qty']++;
                return;
            }
        }

        $this->cart[] = [
            'barang_id' => (int) $barang->barang_id,
            'barang_nama' => $barang->barang_nama,
            'harga' => (int) $barang->harga_jual,
            'qty' => 1,
        ];
    }

    public function incrementQty(int $barangId): void
    {
        $stok = app(PosCheckoutService::class)->getAvailableStock($barangId);

        foreach ($this->cart as &$item) {
            if ((int) $item['barang_id'] === $barangId) {
                if ((int) $item['qty'] + 1 > $stok) {
                    Notification::make()->title('Qty melebihi stok')->warning()->send();
                    return;
                }

                $item['qty']++;
                return;
            }
        }
    }

    public function decrementQty(int $barangId): void
    {
        foreach ($this->cart as $index => &$item) {
            if ((int) $item['barang_id'] === $barangId) {
                $item['qty']--;

                if ((int) $item['qty'] <= 0) {
                    unset($this->cart[$index]);
                    $this->cart = array_values($this->cart);
                }

                return;
            }
        }
    }

    public function removeItem(int $barangId): void
    {
        $this->cart = array_values(array_filter(
            $this->cart,
            fn ($item) => (int) $item['barang_id'] !== $barangId
        ));
    }

    public function getGrandTotalProperty(): int
    {
        return (int) collect($this->cart)->sum(fn ($item) => ((int) $item['harga']) * ((int) $item['qty']));
    }

    public function checkout(): void
    {
        $this->validate([
            'pembeli' => ['required', 'string', 'max:50'],
        ]);

        try {
            $penjualan = app(PosCheckoutService::class)->checkout(
                userId: (int) Auth::id(),
                pembeli: $this->pembeli,
                cart: $this->cart
            );

            $this->cart = [];
            $this->pembeli = 'Umum';

            Notification::make()
                ->title('Transaksi berhasil')
                ->body('Kode transaksi: ' . $penjualan->penjualan_kode)
                ->success()
                ->send();

            $this->redirect(PenjualanResource::getUrl('index'));
        } catch (ValidationException $e) {
            Notification::make()
                ->title('Checkout gagal')
                ->body(collect($e->errors())->flatten()->first())
                ->danger()
                ->send();
        }
    }
}