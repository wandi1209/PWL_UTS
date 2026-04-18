<?php

namespace App\Filament\Resources\Penjualans\Pages;

use App\Filament\Resources\Penjualans\PenjualanResource;
use Filament\Resources\Pages\ListRecords;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    public function getTitle(): string
    {
        return 'Histori Penjualan';
    }

    protected function getHeaderActions(): array
    {
        return [
            // Histori read-only, tidak ada tombol create
            // Input transaksi baru dilakukan di halaman POS kasir
        ];
    }
}
