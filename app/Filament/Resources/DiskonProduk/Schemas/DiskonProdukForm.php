<?php

namespace App\Filament\Resources\DiskonProduk\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DiskonProdukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Diskon Produk')
                    ->schema([
                        Select::make('produk_id')
                            ->label('Produk')
                            ->relationship('produk', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('tipe')
                            ->label('Tipe Diskon')
                            ->options([
                                'percent' => 'Persentase (%)',
                                'fixed' => 'Nominal (Rp)',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->default('percent')
                            ->columnSpan(1),
                        TextInput::make('nilai')
                            ->label('Nilai Diskon')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(
                                fn(Get $get) =>
                                $get('tipe') === 'percent' ? '%' : 'Rp'
                            )
                            ->placeholder(
                                fn(Get $get) =>
                                $get('tipe') === 'percent'
                                ? 'Maksimal 100%'
                                : 'Jumlah potongan dalam Rupiah'
                            )
                            ->columnSpan(1),
                        DatePicker::make('mulai_berlaku')
                            ->label('Mulai Berlaku')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->columnSpan(1),

                        DatePicker::make('berakhir')
                            ->label('Berakhir')
                            ->required()
                            ->native(false)
                            ->after('mulai_berlaku')
                            ->minDate(fn(Get $get) => $get('mulai_berlaku') ?? now())
                            ->columnSpan(1),
                        TextInput::make('label')
                            ->label('Label Diskon')
                            ->required()
                            ->placeholder('Promo Akhir Tahun, Diskon Spesial, dll'),
                        Toggle::make('aktif')
                            ->onColor('success')
                            ->required(),
                    ])->columns(2)->columnSpanFull(),

            ]);
    }
}
