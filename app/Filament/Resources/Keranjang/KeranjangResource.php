<?php

namespace App\Filament\Resources\Keranjang;

use App\Filament\Resources\Keranjang\Pages\CreateKeranjang;
use App\Filament\Resources\Keranjang\Pages\EditKeranjang;
use App\Filament\Resources\Keranjang\Pages\ListKeranjangs;
use App\Filament\Resources\Keranjang\Pages\ViewKeranjang;
use App\Filament\Resources\Keranjang\Schemas\KeranjangForm;
use App\Filament\Resources\Keranjang\Schemas\KeranjangInfolist;
use App\Filament\Resources\Keranjang\Tables\KeranjangsTable;
use App\Models\Keranjang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class KeranjangResource extends Resource
{
    protected static ?string $model = Keranjang::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Produk';
      protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Keranjang';
    protected static ?string $pluralModelLabel = 'Keranjang';
    protected static ?string $navigationLabel = 'Keranjang';
    protected static ?string $slug = 'keranjang';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public static function form(Schema $schema): Schema
    {
        return KeranjangForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KeranjangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KeranjangsTable::configure($table);
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
            'index' => ListKeranjangs::route('/'),
            'create' => CreateKeranjang::route('/create'),
            'view' => ViewKeranjang::route('/{record}'),
            'edit' => EditKeranjang::route('/{record}/edit'),
        ];
    }
}
