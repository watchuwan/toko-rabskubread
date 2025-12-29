<?php

namespace App\Filament\Resources\ItemKeranjang\Pages;

use App\Filament\Resources\ItemKeranjang\ItemKeranjangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemKeranjang extends ViewRecord
{
    protected static string $resource = ItemKeranjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
