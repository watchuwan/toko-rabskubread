<?php

namespace App\Filament\Resources\Produk\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class ProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Informasi Produk')
                            ->badgeColor('produk')
                            ->badge('Produk')
                            ->icon(Heroicon::ShoppingBag)
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                Select::make('kategori_id')
                                    ->label('Kategori')
                                    ->columnSpanFull()
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
                            ])->columns(4)->columnSpanFull(),
                        Tab::make('Informasi Gambar Produk')
                            ->badge('Gambar')
                            ->badgeColor('gambar')
                            ->icon(Heroicon::Photo)
                            ->iconPosition(IconPosition::After)
                            ->schema([
                                // ...
                                Repeater::make("gambar")
                                    ->relationship('gambar')
                                    ->schema([
                                        FileUpload::make('path_gambar')
                                            ->label('Gambar')
                                            ->image()
                                            ->distinct()
                                            ->live(false)
                                            ->directory('produk-images'),
                                        TextInput::make('urutan')
                                            ->numeric()
                                            ->default(0),
                                        Toggle::make('gambar_utama')
                                            ->label('Gambar Utama')
                                            ->onColor('success')
                                            ->default(false)
                                    ])
                                    ->itemLabel(fn(array $state): ?string =>
                                        isset($state['gambar_utama']) && $state['gambar_utama']
                                        ? '⭐ Gambar Utama'
                                        : 'Gambar #' . ($state['urutan'] ?? 0))
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                        // Auto increment urutan jika tidak diisi
                                        if (!isset($data['urutan'])) {
                                            $data['urutan'] = 0;
                                        }
                                        return $data;
                                    })
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->reorderable(true)
                                    ->addActionLabel("Tambah Gambar Produk")
                            ])->columnSpanFull()->columns(2),
                    ]),


            ]);
    }
}
