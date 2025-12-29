<?php

namespace App\Filament\Resources\ItemKeranjang\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemKeranjangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('keranjang_id')
                    ->numeric(),
                TextEntry::make('produk_id')
                    ->numeric(),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
