<?php

namespace App\Filament\Resources\DiskonProduk\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class DiskonProdukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Diskon')
                    ->schema([
                        TextEntry::make('produk.nama')
                            ->label('Produk')
                            ->color('primary')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),

                        TextEntry::make('label')
                            ->label('Label Diskon')
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('tipe')
                            ->label('Tipe Diskon')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'persentase' => 'Persentase',
                                'fixed' => 'Nominal',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'persentase' => 'success',
                                'fixed' => 'info',
                                default => 'gray',
                            }),

                        TextEntry::make('nilai')
                            ->label('Nilai Diskon')
                            ->badge()
                            ->formatStateUsing(fn ($state, $record) => 
                                $record->tipe === 'percent'
                                    ? $state . '%'
                                    : 'Rp ' . number_format($state, 0, ',', '.')
                            )
                            ->color('primary')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                    ])
                    ->columns(4)->columnSpanFull(),

                // Price Cards
                Section::make('Perhitungan Harga')
                    ->schema([
                                TextEntry::make('hemat')
                                    ->hiddenLabel()
                                    ->state(function ($record) {
                                        $hargaAsli = (float) $record->produk->harga;
                                        $nilai = (float) $record->nilai;

                                        if ($record->tipe === 'fixed') {
                                            $potongan = $nilai;
                                        } else {
                                            $potongan = ($hargaAsli * $nilai) / 100;
                                        }

                                        $persen = $hargaAsli > 0 ? ($potongan / $hargaAsli) * 100 : 0;

                                        return '💰 Pelanggan hemat Rp ' . number_format($potongan, 0, ',', '.') . ' (' . number_format($persen, 1) . '%)!';
                                    })
                                    ->size(TextSize::Large)
                                    ->color('warning')
                                    ->weight(FontWeight::Bold),
                        

                                TextEntry::make('harga_asli')
                                    ->label('Harga Asli')
                                    ->state(fn ($record) => 'Rp ' . number_format((float) $record->produk->harga, 0, ',', '.'))
                                    ->color('gray')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('potongan')
                                    ->label('Potongan')
                                    ->state(function ($record) {
                                        $hargaAsli = (float) $record->produk->harga;
                                        $nilai = (float) $record->nilai;

                                        if ($record->tipe === 'fixed') {
                                            $potongan = $nilai;
                                        } else {
                                            $potongan = ($hargaAsli * $nilai) / 100;
                                        }

                                        $persen = $hargaAsli > 0 ? ($potongan / $hargaAsli) * 100 : 0;

                                        return '- Rp ' . number_format($potongan, 0, ',', '.') . ' (' . number_format($persen, 1) . '%)';
                                    })
                                    ->color('danger')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('harga_diskon')
                                    ->label('Harga Setelah Diskon')
                                    ->state(function ($record) {
                                        $hargaAsli = (float) $record->produk->harga;
                                        $hargaDiskon = $record->hitungHargaDiskon($hargaAsli);

                                        return 'Rp ' . number_format($hargaDiskon, 0, ',', '.');
                                    })
                                    ->color('success')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold),
                    ])->columns(4)->columnSpanFull(),

            ]);
    }
}
