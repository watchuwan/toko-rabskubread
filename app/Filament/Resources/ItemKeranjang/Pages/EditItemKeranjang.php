<?php

namespace App\Filament\Resources\ItemKeranjang\Pages;

use App\Filament\Resources\ItemKeranjang\ItemKeranjangResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditItemKeranjang extends EditRecord
{
    protected static string $resource = ItemKeranjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
