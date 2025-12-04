<?php

namespace App\Filament\Resources\Produk;

use App\Filament\Resources\Produk\Pages\CreateProduk;
use App\Filament\Resources\Produk\Pages\EditProduk;
use App\Filament\Resources\Produk\Pages\ListProduks;
use App\Filament\Resources\Produk\Pages\ViewProduk;
use App\Filament\Resources\Produk\ProdukResource\RelationManagers\GambarRelationManager;
use App\Filament\Resources\Produk\Schemas\ProdukForm;
use App\Filament\Resources\Produk\Schemas\ProdukInfolist;
use App\Filament\Resources\Produk\Tables\ProduksTable;
use App\Models\Produk;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Toko';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $slug = 'produk';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProdukForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProdukInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduksTable::configure($table);
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
            'index' => ListProduks::route('/'),
            'create' => CreateProduk::route('/create'),
            'view' => ViewProduk::route('/{record}'),
            'edit' => EditProduk::route('/{record}/edit'),
        ];
    }
}
