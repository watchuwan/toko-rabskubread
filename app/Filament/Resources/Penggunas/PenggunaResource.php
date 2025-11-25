<?php

namespace App\Filament\Resources\Penggunas;

use App\Filament\Resources\Penggunas\Pages\ManagePenggunas;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\Role;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Modelable;
use UnitEnum;

class PenggunaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Pengguna';

        protected static string|UnitEnum|null $navigationGroup = "Manajemen Adminstrator";


    protected static ?int $navigationSort = 0;
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                                                        ->reactive()

                            ->required(),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->reactive()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(function (callable $get, $record) {
                                if (!$record)
                                    return true; // create = wajib
                    
                                $fields = ['name', 'email'];
                                foreach ($fields as $field) {
                                    if ($get($field) !== $record->{$field}) {
                                        return true; // ada yang berubah → wajib password
                                    }
                                }

                                // cek roles berubah
                                $oldRoles = $record->roles->pluck('id')->toArray();
                                $newRoles = $get('roles') ?? [];
                                if (array_diff($oldRoles, $newRoles) || array_diff($newRoles, $oldRoles)) {
                                    return true;
                                }

                                return false;
                            })
                            ->confirmed(),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->label('Konfirmasi Password')
                            ->dehydrated(false)
                            ->required(function (callable $get, $record) {
                                if (!$record)
                                    return true; // create = wajib
                    
                                $fields = ['name', 'email'];
                                foreach ($fields as $field) {
                                    if ($get($field) !== $record->{$field}) {
                                        return true;
                                    }
                                }

                                // cek roles berubah
                                $oldRoles = $record->roles->pluck('id')->toArray();
                                $newRoles = $get('roles') ?? [];
                                if (array_diff($oldRoles, $newRoles) || array_diff($newRoles, $oldRoles)) {
                                    return true;
                                }

                                return filled($get('password'));
                            }),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpanFull()
                            ->searchable()
                            ->label('Peran & Hak Akses')
                            ->options(function () {
                                $user = Filament::auth()->user();

                                if ($user->hasRole('super_admin')) {
                                    // Super admin lihat semua role
                                    return Peran::pluck('name', 'id');
                                }

                                if ($user->hasRole('admin')) {
                                    // Admin lihat semua kecuali super_admin
                                    return Peran::where('name', '!=', 'super_admin')->pluck('name', 'id');
                                }

                                return [];
                            })
                            ->visible(fn() => Filament::auth()->user()?->hasAnyRole(['super_admin', 'admin'])),
                    ])->columns(2)->columnSpanFull()

            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('Roles.name')
                            ->label('Peran & Hak Akses'),
                    ])->columns(3)->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                TextColumn::make('Roles.name')
                    ->label('Peran & Hak Akses'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Filament::auth()->user();

        if ($user->hasRole('super_admin')) {
            // Super admin lihat semua
            return $query;
        }

        // Admin atau user lain
        return $query->whereHas('roles', function ($q) use ($user) {
            // Admin lihat semua kecuali super_admin
            if ($user->hasRole('admin')) {
                $q->where('name', '!=', 'super_admin');
            } else {
                // User biasa hanya lihat dirinya sendiri
                $q->whereIn('name', $user->roles->pluck('name'));
            }
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePenggunas::route('/'),
        ];
    }
}
