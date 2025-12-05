<?php

namespace App\Filament\Resources\Produk\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
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
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $kategori = \App\Models\Kategori::find($state);

                                            if ($kategori && $kategori->sku_prefix) {
                                                // Auto set prefix dari kategori
                                                $prefix = $kategori->sku_prefix;
                                                $set('sku_prefix', $prefix);

                                                // Auto generate SKU
                                                $count = \App\Models\Produk::where('sku', 'like', $prefix . '-%')->count();
                                                $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
                                                $set('sku', $prefix . '-' . $nextNumber);
                                            }
                                        }
                                    })
                                    ->createOptionForm([
                                        TextInput::make('nama')
                                            ->label('Nama Kategori')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('sku_prefix')
                                            ->label('Prefix SKU')
                                            ->required()
                                            ->maxLength(10)
                                            ->placeholder('RTI, KUE, SNK')
                                            ->helperText('Prefix untuk generate SKU produk (3-10 karakter)')
                                            ->reactive()
                                            ->afterStateUpdated(fn($state, callable $set) =>
                                                $set('sku_prefix', strtoupper($state))),

                                        Textarea::make('deskripsi')
                                            ->label('Deskripsi')
                                            ->rows(3),
                                    ]),
                                TextInput::make('nama')
                                    ->required(),
                                TextInput::make('slug')
                                    ->hidden(),
                                TextInput::make('stok')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('harga')
                                    ->prefix('Rp. ')
                                    ->numeric()
                                    ->required(),
                                Select::make('sku_prefix')
                                    ->label('Prefix SKU')
                                    ->options(function () {
                                        // Ambil semua kategori yang punya sku_prefix
                                        return \App\Models\Kategori::whereNotNull('sku_prefix')
                                            ->get()
                                            ->mapWithKeys(fn($kategori) => [
                                                $kategori->sku_prefix => $kategori->sku_prefix . ' - ' . $kategori->nama
                                            ])
                                            ->toArray();
                                    })

                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->helperText('Otomatis dari kategori yang dipilih')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            // Hitung produk dengan prefix ini
                                            $count = \App\Models\Produk::where('sku', 'like', $state . '-%')->count();

                                            // Nomor berikutnya
                                            $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

                                            $set('sku', strtoupper($state) . '-' . $nextNumber);
                                        }
                                    }),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->readOnly()
                                    ->helperText('Otomatis: PREFIX-XXX')
                                    ->columnSpan(2),
                                Textarea::make('deskripsi')
                                    ->columnSpanFull(),
                                Toggle::make('aktif')
                                    ->default(true)
                                    ->onColor('success')
                                    ->required(),
                            ])->columns(3)->columnSpanFull(),
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
                                            ->disk(config('filesystems.default'))
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
