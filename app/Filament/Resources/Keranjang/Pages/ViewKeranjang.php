<?php

namespace App\Filament\Resources\Keranjang\Pages;

use App\Filament\Resources\Keranjang\KeranjangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKeranjang extends ViewRecord
{
    protected static string $resource = KeranjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
