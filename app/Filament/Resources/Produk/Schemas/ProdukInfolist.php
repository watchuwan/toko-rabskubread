<?php

namespace App\Filament\Resources\Produk\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Produk')
            ->schema([ TextEntry::make('nama'),
                TextEntry::make('slug'),
      
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('stok')
                    ->numeric(),
                TextEntry::make('sku')
                    ->label('SKU'),
                IconEntry::make('aktif')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                              TextEntry::make('deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),])->columns(4)->columnSpanFull(),
               
            ]);
    }
}
