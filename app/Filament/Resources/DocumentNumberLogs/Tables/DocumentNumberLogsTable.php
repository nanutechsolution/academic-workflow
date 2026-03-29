<?php

namespace App\Filament\Resources\DocumentNumberLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentNumberLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('formatted_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),

                TextColumn::make('subject')
                    ->label('Perihal / Dokumen')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('Petugas')
                    ->description(fn($record) => $record->created_at->diffForHumans()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('document_category_id')
                    ->label('Filter Kategori')
                    ->relationship('category', 'name'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
