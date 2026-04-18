<?php

namespace App\Filament\Resources\Stoks\Pages;

use App\Filament\Resources\Stoks\StokResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStok extends EditRecord
{
    protected static string $resource = StokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
