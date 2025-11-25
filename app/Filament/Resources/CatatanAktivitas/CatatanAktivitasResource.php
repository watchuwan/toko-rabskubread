<?php

namespace App\Filament\Resources\CatatanAktivitas;

use App\Filament\Resources\CatatanAktivitas\Pages\ManageCatatanAktivitas;
use App\Models\CatatanAktivitas;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CatatanAktivitasResource extends Resource
{
    protected static ?string $model = CatatanAktivitas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'log_name';
    protected static string|UnitEnum|null $navigationGroup = "Manajemen Adminstrator";
    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('log_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Catatan Aktivitas')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn(?string $state) => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                        default => ucfirst($state ?? 'Tidak diketahui'),
                    })
                    ->color(fn(?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'primary',
                        'deleted' => 'warning',
                        default => 'create-btn',
                    })
                    ->placeholder('N/A'),


                TextColumn::make('subject_name')
                    ->label('Subjek')
                    ->getStateUsing(function (CatatanAktivitas $record): string {
                        // Ambil nama model tanpa namespace
                        return class_basename($record->subject_type ?? 'N/A');
                    })
                    ->placeholder('N/A'),

                TextColumn::make('causer_name')
                    ->label('Peran')
                    ->getStateUsing(function (CatatanAktivitas $record): ?string {
                        if (!$record->causer)
                            return 'System';
                        return $record->causer->name ?? "User #{$record->causer_id}";
                    })
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->getStateUsing(function (CatatanAktivitas $record): string {
                        // Ambil deskripsi bawaan dari activity log
                        $baseDescription = trim($record->description ?? '');

                        // Helper dinamis untuk ambil semua field relevan
                        $getAllFields = function (array $data): string {
                            if (empty($data)) {
                                return 'N/A';
                            }

                            // Field yang tidak penting
                            $excluded = ['id', 'created_at', 'updated_at', 'deleted_at', 'password'];

                            // Filter field kosong/null
                            $filtered = array_filter($data, function ($value, $key) use ($excluded) {
                                return !in_array($key, $excluded) && !is_null($value) && $value !== '';
                            }, ARRAY_FILTER_USE_BOTH);

                            if (empty($filtered)) {
                                return 'N/A';
                            }

                            // Format hasil key: value
                            $pairs = [];
                            foreach ($filtered as $key => $value) {
                                if (is_scalar($value)) {
                                    $pairs[] = "{$key}: {$value}";
                                } elseif (is_array($value)) {
                                    $pairs[] = "{$key}: " . json_encode($value);
                                }
                            }

                            return implode(', ', $pairs);
                        };

                        // Ambil field dinamis dari subject atau properties
                        if ($record->subject) {
                            $subjectArray = $record->subject->toArray();
                            $dynamicDetails = $getAllFields($subjectArray);
                        } else {
                            $properties = $record->properties;
                            if (is_object($properties)) {
                                $properties = $properties->toArray();
                            }

                            $oldData = $properties['old'] ?? $properties['attributes'] ?? [];
                            $dynamicDetails = $getAllFields($oldData);
                        }

                        // Gabungkan deskripsi dengan field dinamis
                        if ($baseDescription && $baseDescription !== 'N/A') {
                            return "{$baseDescription} — {$dynamicDetails}";
                        }

                        return $dynamicDetails ?: 'N/A';
                    })
                    ->placeholder('N/A'),

            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ])->defaultPaginationPageOption(25);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCatatanAktivitas::route('/'),
        ];
    }
}
