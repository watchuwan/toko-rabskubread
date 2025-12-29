<?php

namespace App\Filament\Resources\Keranjang\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class KeranjangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Informasi Keranjang')
                            ->badgeColor('keranjang')
                            ->badge('Keranjang')
                            ->icon(Heroicon::ShoppingCart)
                            ->iconPosition('after')
                            ->schema([
                                Select::make('pelanggan_id')
                                    ->relationship('pelanggan', 'nama')
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                        Tab::make('Item Keranjang')
                            ->badge('Item Keranjang')
                            ->badgeColor('itemkeranjang')
                            ->icon(Heroicon::ShoppingBag)
                            ->iconPosition('after')
                            ->schema(
                                [
                                    Repeater::make('items')
                                        ->relationship('items')
                                        ->schema([
                                            Select::make('produk_id')
                                                ->label('Produk')
                                                ->relationship('produk', 'nama')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                    $produk = \App\Models\Produk::find($state);
                                                    if ($produk) {
                                                        $set('harga', $produk->harga);
                                                    }
                                                    // Update subtotal setelah harga berubah
                                                    $harga = (float) ($get('harga') ?? 0);
                                                    $jumlah = (int) ($get('jumlah') ?? 1);
                                                    $set('subtotal', number_format($harga * $jumlah, 2, ',', '.'));
                                                })
                                                ->columnSpan(2),

                                            TextInput::make('harga')
                                                ->label('Harga')
                                                ->numeric()
                                                ->required()
                                                ->prefix('Rp')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set) {
                                                    $harga = (float) ($get('harga') ?? 0);
                                                    $jumlah = (int) ($get('jumlah') ?? 0);
                                                    $set('subtotal', number_format($harga * $jumlah, 2, ',', '.'));
                                                }),

                                            TextInput::make('jumlah')
                                                ->label('Jumlah')
                                                ->numeric()
                                                ->required()
                                                ->default(1)
                                                ->minValue(1)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set) {
                                                    $harga = (float) ($get('harga') ?? 0);
                                                    $jumlah = (int) ($get('jumlah') ?? 0);
                                                    $set('subtotal', number_format($harga * $jumlah, 2, ',', '.'));
                                                }),

                                            TextInput::make('subtotal')
                                                ->label('Subtotal')
                                                ->prefix('Rp')
                                                ->disabled()
                                                ->dehydrated(false)
                                                ->columnSpan(2),



                                        ])
                                ]
                            )

                    ])


            ]);
    }
}
