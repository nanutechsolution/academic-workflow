<?php

namespace App\Filament\Resources\Organizations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'university' => 'danger',
                        'faculty' => 'warning',
                        'department' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('parent.name')
                    ->label('Induk'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'faculty' => 'Fakultas',
                        'department' => 'Prodi',
                    ]),
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
