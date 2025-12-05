<?php

namespace App\Filament\Resources\Pelanggan\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Informasi Pelanggan')
                            ->badgeColor('pelanggan')
                            ->icon(Heroicon::UserCircle)
                            ->iconPosition('after')
                            ->badge('Pelanggan')
                            ->schema([
                                Section::make('Informasi Pelanggan')
                                    ->schema([
                                        FileUpload::make('foto_profil')
                                            ->image()
                                                                                                                ->disk(config('filesystems.default'))
                                            ->distinct()
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

                                    ])->columnSpanFull()->columns(2),

                            ]),
                        Tab::make('Alamat')
                            ->iconPosition('after')
                            ->badge('Alamat')
                            ->badgeColor('alamat')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make('Daftar Alamat')
                                    ->description('Kelola alamat pelanggan')
                                    ->schema([
                                        Repeater::make('alamat')
                                            ->relationship('alamat')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->required()
                                                    ->label('Label Alamat')
                                                    ->columnSpan(2)
                                                    ->placeholder('Rumah, Kantor, dll'),
                                                TextInput::make('nama_penerima')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->label('Nama Penerima'),
                                                Toggle::make('alamat_utama')
                                                    ->label('Jadikan Alamat Utama')
                                                    ->onColor('success')
                                                    ->default(true),

                                                Textarea::make('alamat')
                                                    ->required()
                                                    ->rows(3)
                                                    ->label('Alamat Lengkap')
                                                    ->columnSpanFull(),

                                                TextInput::make('kota')
                                                    ->required()

                                                    ->label('Kota'),

                                                TextInput::make('provinsi')
                                                    ->required()
                                                    ->label('Provinsi'),

                                                TextInput::make('kode_pos')
                                                    ->required()
                                                    ->numeric()
                                                    ->label('Kode Pos'),

                                                TextInput::make('telepon')
                                                    ->tel()
                                                    ->maxLength(20)
                                                    ->label('Nomor Telepon Penerima'),

                                            ])
                                            ->columns(4)
                                            ->defaultItems(0)
                                            ->addActionLabel('Tambah Alamat')
                                            ->collapsible()
                                            ->itemLabel(fn(array $state): ?string => $state['nama'] ?? null)
                                            ->collapsed(false)
                                            ->cloneable()
                                            ->reorderable()
                                            ->deleteAction(
                                                fn($action) => $action->requiresConfirmation()
                                            ),
                                    ]),
                            ])
                    ])->columnSpanFull()
                    ->persistTabInQueryString(),

            ]);
    }
}
