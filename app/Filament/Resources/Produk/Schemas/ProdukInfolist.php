<?php

namespace App\Filament\Resources\Produk\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
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
                    ->schema([
                        TextEntry::make('kategori.nama')
                            ->label('Kategori'),
                        TextEntry::make('nama'),
                        TextEntry::make('slug'),
                        TextEntry::make('harga')
                            ->money('IDR'),
                        TextEntry::make('stok')
                            ->badge()
                            ->color(fn($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
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
                            ->columnSpanFull(),
                    ])->columns(5)->columnSpanFull(),

Section::make('Gambar Produk')
    ->schema([
        RepeatableEntry::make('gambar')
            ->hiddenLabel()
            ->schema([
                ImageEntry::make('path_gambar')
                    ->hiddenLabel()
                    ->size(80)
                    ->circular(),
                       TextEntry::make('gambar_utama')
                    ->label('')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? '⭐ Utama' : 'Biasa')
                    ->color(fn ($state) => $state ? 'warning' : 'gray'),
                
                TextEntry::make('urutan')
                    ->label('Urutan')
                    ->badge()
                    ->color('info')
                    ->prefix('#'),
            
            
            ])
            ->columns(3)
            ->grid(3),
    ])
    ->columnSpanFull(),


            ]);
    }
}
