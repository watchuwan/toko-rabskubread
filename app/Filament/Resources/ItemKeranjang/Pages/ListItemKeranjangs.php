<?php

namespace App\Filament\Resources\ItemKeranjang\Pages;

use App\Filament\Resources\ItemKeranjang\ItemKeranjangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItemKeranjangs extends ListRecords
{
    protected static string $resource = ItemKeranjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
