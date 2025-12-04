<?php

namespace App\Filament\Resources\DiskonProduk\Pages;

use App\Filament\Resources\DiskonProduk\DiskonProdukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiskonProduks extends ListRecords
{
    protected static string $resource = DiskonProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
