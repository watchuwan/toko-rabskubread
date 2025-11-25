<?php

namespace App\Filament\Resources\CatatanAktivitas\Pages;

use App\Filament\Resources\CatatanAktivitas\CatatanAktivitasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCatatanAktivitas extends ManageRecords
{
    protected static string $resource = CatatanAktivitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
