<?php

namespace App\Filament\Resources\Penggunas\Pages;

use App\Filament\Resources\Penggunas\PenggunaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePenggunas extends ManageRecords
{
    protected static string $resource = PenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
