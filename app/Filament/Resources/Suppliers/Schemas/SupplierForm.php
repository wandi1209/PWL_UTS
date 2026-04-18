<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('supplier_kode')
                    ->label('Kode Supplier')
                    ->required()
                    ->maxLength(10),
                TextInput::make('supplier_nama')
                    ->label('Nama Supplier')
                    ->required()
                    ->maxLength(100),
                TextInput::make('supplier_alamat')
                    ->label('Alamat Supplier')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
