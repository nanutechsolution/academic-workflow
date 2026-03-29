<?php

namespace App\Filament\Resources\AcademicEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AcademicEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('color')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'danger' => 'danger',
                        'warning' => 'warning',
                        'success' => 'success',
                        'primary' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'danger' => 'Kritis',
                        'warning' => 'Penting',
                        'success' => 'Selesai',
                        'primary' => 'Normal',
                        default => $state,
                    }),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('color')
                    ->label('Prioritas')
                    ->options([
                        'primary' => 'Normal (Biru)',
                        'success' => 'Selesai (Hijau)',
                        'warning' => 'Penting (Kuning)',
                        'danger' => 'Kritis (Merah)',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
