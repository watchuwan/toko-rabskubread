<?php

namespace App\Filament\Resources\Pelanggan\Pages;

use App\Filament\Resources\Pelanggan\PelangganResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPelanggan extends ViewRecord
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
