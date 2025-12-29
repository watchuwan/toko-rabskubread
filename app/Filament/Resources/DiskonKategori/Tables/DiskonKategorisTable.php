<?php

namespace App\Filament\Resources\DiskonKategori\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiskonKategorisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'percent' => 'Persentase',
                        'fixed' => 'Nominal',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'percent' => 'success',
                        'fixed' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('nilai')
                    ->numeric()
                    ->badge()
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->tipe === 'percent'
                        ? $state . '%'
                        : 'Rp ' . number_format($state, 0, ',', '.')
                    )
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('mulai_berlaku')
                    ->date()
                    ->sortable(),
                TextColumn::make('berakhir')
                    ->date()
                    ->sortable(),
                IconColumn::make('aktif')
                    ->boolean(),
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
