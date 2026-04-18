<?php

namespace App\Filament\Resources\Penjualans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PenjualanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('pembeli')
                    ->required(),
                TextInput::make('penjualan_kode')
                    ->required(),
                DateTimePicker::make('penjualan_tanggal')
                    ->required(),
            ]);
    }
}
