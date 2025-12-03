<?php

namespace App\Filament\Resources\Produk\Pages;

use App\Filament\Resources\Produk\ProdukResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
