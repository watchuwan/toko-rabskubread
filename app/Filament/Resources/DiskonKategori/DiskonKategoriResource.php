<?php

namespace App\Filament\Resources\DiskonKategori;

use App\Filament\Resources\DiskonKategori\Pages\CreateDiskonKategori;
use App\Filament\Resources\DiskonKategori\Pages\EditDiskonKategori;
use App\Filament\Resources\DiskonKategori\Pages\ListDiskonKategoris;
use App\Filament\Resources\DiskonKategori\Pages\ViewDiskonKategori;
use App\Filament\Resources\DiskonKategori\Schemas\DiskonKategoriForm;
use App\Filament\Resources\DiskonKategori\Schemas\DiskonKategoriInfolist;
use App\Filament\Resources\DiskonKategori\Tables\DiskonKategorisTable;
use App\Models\DiskonKategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiskonKategoriResource extends Resource
{
    protected static ?string $model = DiskonKategori::class;

    protected static ?string $modelLabel = 'Diskon Kategori';
    protected static ?string $pluralModelLabel = 'Diskon Kategori';
    protected static ?string $navigationLabel = 'Diskon Kategori';
    protected static ?string $slug = 'diskon-kategori';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DiskonKategoriForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DiskonKategoriInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiskonKategorisTable::configure($table);
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
            'index' => ListDiskonKategoris::route('/'),
            'create' => CreateDiskonKategori::route('/create'),
            'view' => ViewDiskonKategori::route('/{record}'),
            'edit' => EditDiskonKategori::route('/{record}/edit'),
        ];
    }
}
