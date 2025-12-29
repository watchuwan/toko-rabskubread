<?php

namespace App\Filament\Resources\ItemKeranjang;

use App\Filament\Resources\ItemKeranjang\Pages\CreateItemKeranjang;
use App\Filament\Resources\ItemKeranjang\Pages\EditItemKeranjang;
use App\Filament\Resources\ItemKeranjang\Pages\ListItemKeranjangs;
use App\Filament\Resources\ItemKeranjang\Pages\ViewItemKeranjang;
use App\Filament\Resources\ItemKeranjang\Schemas\ItemKeranjangForm;
use App\Filament\Resources\ItemKeranjang\Schemas\ItemKeranjangInfolist;
use App\Filament\Resources\ItemKeranjang\Tables\ItemKeranjangsTable;
use App\Models\ItemKeranjang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ItemKeranjangResource extends Resource
{
    protected static ?string $model = ItemKeranjang::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Produk';
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Item Keranjang';
    protected static ?string $pluralModelLabel = 'Item Keranjang';
    protected static ?string $navigationLabel = 'Item Keranjang';
    protected static ?string $slug = 'item-keranjang';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return ItemKeranjangForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemKeranjangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemKeranjangsTable::configure($table);
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
            'index' => ListItemKeranjangs::route('/'),
            'create' => CreateItemKeranjang::route('/create'),
            'view' => ViewItemKeranjang::route('/{record}'),
            'edit' => EditItemKeranjang::route('/{record}/edit'),
        ];
    }
}
