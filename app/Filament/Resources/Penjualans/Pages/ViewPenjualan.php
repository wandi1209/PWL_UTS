<?php

namespace App\Filament\Resources\Penjualans\Pages;

use App\Filament\Resources\Penjualans\PenjualanResource;
use App\Models\Penjualan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewPenjualan extends Page
{
    protected static string $resource = PenjualanResource::class;
    protected string $view = 'filament.resources.penjualans.pages.view-penjualan';

    public Penjualan $record;

    public function getTitle(): string|Htmlable
    {
        return 'Detail Transaksi #' . $this->record->penjualan_kode;
    }
}
