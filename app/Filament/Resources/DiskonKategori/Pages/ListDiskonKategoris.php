<?php

namespace App\Filament\Resources\DiskonKategori\Pages;

use App\Filament\Resources\DiskonKategori\DiskonKategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiskonKategoris extends ListRecords
{
    protected static string $resource = DiskonKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
