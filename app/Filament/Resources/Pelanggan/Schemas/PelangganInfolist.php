<?php

namespace App\Filament\Resources\Pelanggan\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PelangganInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pelanggan')
                    ->schema([
                        ImageEntry::make('foto_profil')
                            ->disk('public')
                            ->height(80)
                            ->hiddenLabel()
                            ->circular()
                            ->placeholder('-'),
                        TextEntry::make('nama'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('telepon')
                            ->placeholder('-'),
                        TextEntry::make('tanggal_lahir')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('jenis_kelamin')
                            ->placeholder('-')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'laki-laki' => 'laki-laki',
                                'perempuan' => 'perempuan',
                            }),
                    ])->columns(4)->columnSpanFull(),


            ]);
    }
}
