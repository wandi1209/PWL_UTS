<?php

namespace App\Filament\Resources\Penjualans\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenjualansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penjualan_kode')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penjualan_tanggal')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                TextColumn::make('pembeli')
                    ->label('Pembeli')
                    ->searchable(),
                TextColumn::make('user.nama')
                    ->label('Kasir')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Detail'),
            ])
            ->toolbarActions([
                // Histori read-only, tidak ada aksi bulk
            ])
            ->defaultSort('penjualan_tanggal', 'desc');
    }
}
