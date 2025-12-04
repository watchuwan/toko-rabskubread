<?php

namespace App\Filament\Resources\DiskonProduk;

use App\Filament\Resources\DiskonProduk\Pages\CreateDiskonProduk;
use App\Filament\Resources\DiskonProduk\Pages\EditDiskonProduk;
use App\Filament\Resources\DiskonProduk\Pages\ListDiskonProduks;
use App\Filament\Resources\DiskonProduk\Pages\ViewDiskonProduk;
use App\Filament\Resources\DiskonProduk\Schemas\DiskonProdukForm;
use App\Filament\Resources\DiskonProduk\Schemas\DiskonProdukInfolist;
use App\Filament\Resources\DiskonProduk\Tables\DiskonProduksTable;
use App\Models\DiskonProduk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DiskonProdukResource extends Resource
{
    protected static ?string $model = DiskonProduk::class;
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Produk';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Diskon Produk';
    protected static ?string $pluralModelLabel = 'Diskon Produk';
    protected static ?string $navigationLabel = 'Diskon Produk';
    protected static ?string $slug = 'diskon-produk';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return DiskonProdukForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DiskonProdukInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiskonProduksTable::configure($table);
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
            'index' => ListDiskonProduks::route('/'),
            'create' => CreateDiskonProduk::route('/create'),
            'view' => ViewDiskonProduk::route('/{record}'),
            'edit' => EditDiskonProduk::route('/{record}/edit'),
        ];
    }
}
