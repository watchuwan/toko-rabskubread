<?php

namespace App\Filament\Resources\DiskonKategori\Pages;

use App\Filament\Resources\DiskonKategori\DiskonKategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDiskonKategori extends EditRecord
{
    protected static string $resource = DiskonKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
