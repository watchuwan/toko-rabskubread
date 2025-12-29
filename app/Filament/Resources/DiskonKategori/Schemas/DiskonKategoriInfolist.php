<?php

namespace App\Filament\Resources\DiskonKategori\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiskonKategoriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Detail Diskon Kategori")
                    ->schema([
                        TextEntry::make('kategori.nama'),
                        TextEntry::make('tipe')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'percent' => 'Persentase',
                                'fixed' => 'Nominal',
                                default => $state,
                            })
                             ->color(fn(string $state): string => match ($state) {
                                'percent' => 'success',
                                'fixed' => 'info',
                                default => 'gray',
                            }),
                        TextEntry::make('nilai')
                            ->numeric()
                            ->badge()
                            ->formatStateUsing(
                                fn($state, $record) =>
                                $record->tipe === 'percent'
                                ? $state . '%'
                                : 'Rp ' . number_format($state, 0, ',', '.')
                            )
                            ->color('primary'),
                        TextEntry::make('mulai_berlaku')
                            ->date(),
                        TextEntry::make('berakhir')
                            ->date(),
                        IconEntry::make('aktif')
                            ->boolean(),
                        TextEntry::make('label')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])->columns(3)->columnSpanFull()

            ]);
    }
}
