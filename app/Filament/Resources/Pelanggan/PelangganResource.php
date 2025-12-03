<?php

namespace App\Filament\Resources\Pelanggan;

use App\Filament\Resources\Pelanggan\Pages\CreatePelanggan;
use App\Filament\Resources\Pelanggan\Pages\EditPelanggan;
use App\Filament\Resources\Pelanggan\Pages\ListPelanggans;
use App\Filament\Resources\Pelanggan\Pages\ViewPelanggan;
use App\Filament\Resources\Pelanggan\Schemas\PelangganForm;
use App\Filament\Resources\Pelanggan\Schemas\PelangganInfolist;
use App\Filament\Resources\Pelanggan\Tables\PelanggansTable;
use App\Models\Pelanggan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $slug = 'pelanggan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return PelangganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PelangganInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PelanggansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPelanggans::route('/'),
            'create' => CreatePelanggan::route('/create'),
            'view' => ViewPelanggan::route('/{record}'),
            'edit' => EditPelanggan::route('/{record}/edit'),
        ];
    }
}
