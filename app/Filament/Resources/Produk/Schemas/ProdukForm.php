<?php

namespace App\Filament\Resources\Produk\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->preload()
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('slug')
                    ->hidden(),
                TextInput::make('stok')
                    ->required() 
                    ->numeric()
                    ->default(0),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->readOnly()
                    ->unique(ignoreRecord: true)
                    ->default(function () {
                        // Auto generate: PRD-timestamp-random
                        return 'RTI-' . str_pad(
                            \App\Models\Produk::max('id') + 1,
                            3,
                            '0',
                            STR_PAD_LEFT
                        );
                    }),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Toggle::make('aktif')
                    ->default(true)
                    ->onColor('success')
                    ->required(),

            ]);
    }
}
