<?php

namespace App\Filament\Resources\DiskonProduk\Pages;

use App\Filament\Resources\DiskonProduk\DiskonProdukResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDiskonProduk extends EditRecord
{
    protected static string $resource = DiskonProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
