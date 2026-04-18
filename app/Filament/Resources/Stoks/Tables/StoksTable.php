<?php

namespace App\Filament\Resources\Stoks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.barang_nama')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supplier.supplier_nama')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.nama')
                    ->label('Diinput Oleh')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('stok_tanggal')
                    ->label('Tanggal Stok')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('stok_jumlah')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
