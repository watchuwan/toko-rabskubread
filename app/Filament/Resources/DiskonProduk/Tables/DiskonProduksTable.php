<?php

namespace App\Filament\Resources\DiskonProduk\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiskonProduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produk.nama')
                    ->sortable(),
                TextColumn::make('tipe')
                    ->searchable(),
                TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mulai_berlaku')
                    ->date()
                    ->sortable(),
                TextColumn::make('berakhir')
                    ->date()
                    ->sortable(),
                IconColumn::make('aktif')
                    ->boolean(),
                TextColumn::make('label')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Preview Diskon')
                    ->icon('heroicon-o-calculator')
                    ->color('preview')
                    ->button()
                    ->modalWidth('3xl')
                    ->modalAlignment('center')
                    ->modalHeading(fn($record) => 'Preview: ' . $record->label)
                    ->modalDescription(fn($record) => 'Perhitungan diskon untuk produk: ' . $record->produk->nama)
                    ->infolist(fn($record) => [
                        // Header Info
                        Section::make('Informasi Diskon')
                            ->schema([
                                TextEntry::make('produk.nama')
                                    ->label('Produk')
                                    ->color('primary')
                                    ->size(TextSize::Large)
                                    ->weight('bold'),

                                TextEntry::make('label')
                                    ->label('Label Diskon')
                                    ->badge()
                                    ->color('warning'),

                                TextEntry::make('tipe')
                                    ->label('Tipe Diskon')
                                    ->badge()
                                    ->formatStateUsing(fn() => $record->tipe === 'percent' ? 'Persentase' : 'Nominal')
                                    ->color(fn() => $record->tipe === 'percent' ? 'success' : 'info'),

                                TextEntry::make('nilai')
                                    ->label('Nilai Diskon')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn() =>
                                        $record->tipe === 'percent'
                                        ? $record->nilai . '%'
                                        : 'Rp ' . number_format($record->nilai, 0, ',', '.')
                                    )
                                    ->color('primary')
                                    ->size(TextSize::Large)
                                    ->weight('bold'),
           
                            ])
                            ->columns(4),

                        // Price Cards
                        Section::make('Perhitungan Harga')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                                             TextEntry::make('hemat')
                                    ->hiddenLabel()
                                    ->state(function () use ($record) {
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
                                      ->columnSpanFull()
                                      ->alignCenter()
                                    ->color('warning')
                                    ->weight('bold'),
                                        TextEntry::make('harga_asli')
                                            ->label('Harga Asli')
                                            ->state(fn() => 'Rp ' . number_format((float) $record->produk->harga, 0, ',', '.'))
                                            ->color('gray')
                                            ->size(TextSize::Large)
                                            ->weight('bold'),

                                        TextEntry::make('potongan')
                                            ->label('Potongan')
                                            ->state(function () use ($record) {
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
                                            ->weight('bold'),

                                        TextEntry::make('harga_diskon')
                                            ->label('Harga Setelah Diskon')
                                            ->state(function () use ($record) {
                                                $hargaAsli = (float) $record->produk->harga;
                                                $hargaDiskon = $record->hitungHargaDiskon($hargaAsli);

                                                return 'Rp ' . number_format($hargaDiskon, 0, ',', '.');
                                            })
                                            ->color('success')
                                            ->size(TextSize::Large)
                                            ->weight('bold'),
                                    ]),
                            ]),

                        // Detail Perhitungan
                        Section::make('Detail Perhitungan')
                            ->schema([
                                KeyValueEntry::make('perhitungan')
                                    ->label('')
                                    ->keyLabel('Item')
                                    ->valueLabel('Nilai')
                                    ->state(function () use ($record) {
                                        $hargaAsli = (float) $record->produk->harga;
                                        $nilai = (float) $record->nilai;

                                        if ($record->tipe === 'fixed') {
                                            $potongan = $nilai;
                                            $hargaDiskon = max(0, $hargaAsli - $nilai);
                                        } else {
                                            $potongan = ($hargaAsli * $nilai) / 100;
                                            $hargaDiskon = $hargaAsli - $potongan;
                                        }

                                        $data = [
                                            'Harga Asli' => 'Rp ' . number_format($hargaAsli, 0, ',', '.'),
                                        ];

                                        if ($record->tipe === 'persentase') {
                                            $data['Diskon ' . $nilai . '%'] = number_format($hargaAsli, 0, ',', '.') . ' × ' . $nilai . '% = Rp ' . number_format($potongan, 0, ',', '.');
                                        } else {
                                            $data['Potongan Nominal'] = '- Rp ' . number_format($potongan, 0, ',', '.');
                                        }

                                        $data['Harga Final'] = 'Rp ' . number_format($hargaDiskon, 0, ',', '.');

                                        return $data;
                                    }),
                            ])
                            ->collapsed(true)
                            ->collapsible(),


                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
