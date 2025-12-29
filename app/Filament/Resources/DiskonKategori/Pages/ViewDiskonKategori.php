<?php

namespace App\Filament\Resources\DiskonKategori\Pages;

use App\Filament\Resources\DiskonKategori\DiskonKategoriResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDiskonKategori extends ViewRecord
{
    protected static string $resource = DiskonKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
