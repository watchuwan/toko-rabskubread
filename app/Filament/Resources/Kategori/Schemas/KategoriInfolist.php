<?php

namespace App\Filament\Resources\Kategori\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kategori')
                    ->schema([
                        TextEntry::make('nama'),
                        TextEntry::make('slug'),
                        TextEntry::make('sku_prefix'),
                        IconEntry::make('aktif')
                            ->boolean(),
                        TextEntry::make('deskripsi')
                            ->placeholder('-')

                    ])->columns(4)->columnSpanFull(),

            ]);
    }
}
