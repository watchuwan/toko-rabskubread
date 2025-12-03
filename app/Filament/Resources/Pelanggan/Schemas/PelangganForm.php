<?php

namespace App\Filament\Resources\Pelanggan\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelanggan')
                    ->schema([
                        FileUpload::make('foto_profil')
                            ->image()
                            ->disk('public')
                            ->directory('pelanggan-photos')
                            ->columnSpanFull(),
                        TextInput::make('nama')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        DateTimePicker::make('email_verified_at')->hidden()->default(now()),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context) => $context === 'create'),
                        TextInput::make('telepon')
                            ->tel(),
                        DatePicker::make('tanggal_lahir'),
                        Select::make('jenis_kelamin')
                            ->options([
                                'laki-laki' => 'Laki-laki',
                                'perempuan' => 'Perempuan',
                            ]),

                    ])->columnSpanFull()->columns(2)
            ]);
    }
}
