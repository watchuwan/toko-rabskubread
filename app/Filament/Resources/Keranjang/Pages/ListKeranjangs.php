<?php

namespace App\Filament\Resources\Keranjang\Pages;

use App\Filament\Resources\Keranjang\KeranjangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKeranjangs extends ListRecords
{
    protected static string $resource = KeranjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
