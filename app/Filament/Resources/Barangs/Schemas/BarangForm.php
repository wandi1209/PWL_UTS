<?php

namespace App\Filament\Resources\Barangs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Barang')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('barang_kode')
                            ->label('Kode Barang')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true),
                        TextInput::make('barang_nama')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(100),
                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'kategori_nama')
                            ->required(),
                    ]),
                Section::make('Informasi Harga')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        TextInput::make('harga_jual')
                            ->label('Harga Jual')
                            ->required()
                            ->numeric(),
                        TextInput::make('harga_beli')
                            ->label('Harga Beli')
                            ->required()
                            ->numeric(),
                    ]),
            ]);
    }
}
