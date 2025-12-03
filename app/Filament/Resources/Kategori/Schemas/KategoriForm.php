<?php

namespace App\Filament\Resources\Kategori\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->schema([
                        TextInput::make('nama')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('slug')
                            ->hidden(),
                        Textarea::make('deskripsi')
                            ->columnSpanFull(),
                        Toggle::make('aktif')
                            ->onColor('success')
                            ->default(true)
                            ->required(),
                    ])->columnSpanFull()

            ]);
    }
}
