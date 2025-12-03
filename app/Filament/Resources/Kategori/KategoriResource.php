<?php

namespace App\Filament\Resources\Kategori;

use App\Filament\Resources\Kategori\Pages\CreateKategori;
use App\Filament\Resources\Kategori\Pages\EditKategori;
use App\Filament\Resources\Kategori\Pages\ListKategoris;
use App\Filament\Resources\Kategori\Pages\ViewKategori;
use App\Filament\Resources\Kategori\Schemas\KategoriForm;
use App\Filament\Resources\Kategori\Schemas\KategoriInfolist;
use App\Filament\Resources\Kategori\Tables\KategorisTable;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;


protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $slug = 'kategori';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KategoriForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KategoriInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategorisTable::configure($table);
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
            'index' => ListKategoris::route('/'),
            'create' => CreateKategori::route('/create'),
            'view' => ViewKategori::route('/{record}'),
            'edit' => EditKategori::route('/{record}/edit'),
        ];
    }
}
