<?php

namespace App\Filament\Resources\Keranjang\Schemas;

use App\Models\ItemKeranjang;
use App\Models\Keranjang;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KeranjangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Keranjang')
                    ->schema([
                        TextEntry::make('pelanggan.nama')
                            ->label('Nama Pelanggan'),
                        TextEntry::make('pelanggan.email')
                            ->label('Email Pelanggan'),
                        TextEntry::make('pelanggan.telepon')
                            ->label('Telepon')
                            ->placeholder('Tidak ada'),
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
                Section::make('Ringkasan Total')
                    ->schema([
                        TextEntry::make('total_item')
                            ->label('Total Item')
                            ->state(fn(Keranjang $record): int => $record->totalItem())
                            ->badge()
                            ->color('keranjang')
                            ->size('lg'),
                        TextEntry::make('total_harga')
                            ->label('Total Harga')
                            ->state(fn(Keranjang $record): float => $record->totalHarga())
                            ->money('IDR')
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(2),
                Section::make('Daftar Item Keranjang')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('produk.nama')
                                    ->label('Produk')
                                    ->weight('bold')
                                    ->size('lg'),
                                TextEntry::make('produk.sku')
                                    ->label('Kode Produk')
                                    ->placeholder('-'),
                                TextEntry::make('harga')
                                    ->label('Harga Satuan')
                                    ->money('IDR'),
                                TextEntry::make('jumlah')
                                    ->label('Jumlah')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->state(fn(ItemKeranjang $record): float => $record->subtotal())
                                    ->money('IDR')
                                    ->weight('bold')
                                    ->color('success'),
                            ])
                            ->columns(5)
                            ->contained(true),
                    ])
                    ->columnSpanFull()
                    ->collapsed(false),


            ]);
    }
}
