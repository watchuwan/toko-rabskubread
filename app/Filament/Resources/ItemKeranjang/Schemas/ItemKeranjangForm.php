<?php

namespace App\Filament\Resources\ItemKeranjang\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemKeranjangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('keranjang_id')
                    ->required()
                    ->numeric(),
                TextInput::make('produk_id')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
            ]);
    }
}
