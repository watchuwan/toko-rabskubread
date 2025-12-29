<?php

namespace App\Filament\Resources\Keranjang\Tables;

use App\Models\Keranjang;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KeranjangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pelanggan.email')
                    ->label('Email Pelanggan')
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Total Item')
                    ->color('keranjang')
                    ->badge()
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->state(fn(Keranjang $record): float => $record->totalHarga())
                    ->money('IDR')
                    ->sortable(),
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
