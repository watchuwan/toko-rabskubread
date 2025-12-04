<?php

namespace App\Filament\Resources\DiskonProduk\Pages;

use App\Filament\Resources\DiskonProduk\DiskonProdukResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDiskonProduk extends ViewRecord
{
    protected static string $resource = DiskonProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
