<?php

namespace App\Filament\Resources\Stoks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('barang_id')
                    ->label('Nama Barang')
                    ->relationship('barang', 'barang_nama')
                    ->required(),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->required()
                    ->options(function () {
                        return \App\Models\Supplier::pluck('supplier_nama', 'supplier_id');
                    }),
                Select::make('user_id')
                    ->relationship('user', 'nama')
                    ->default(\Illuminate\Support\Facades\Auth::id())
                    ->disabled() // User tidak bisa mengganti
                    ->dehydrated() // Tetap dikirim ke database saat simpan
                    ->label('Input Oleh'),
                DateTimePicker::make('stok_tanggal')
                    ->label('Tanggal Stok')
                    ->date()
                    ->required(),
                TextInput::make('stok_jumlah')
                    ->label('Jumlah Stok')
                    ->required()
                    ->numeric(),
            ]);
    }
}
