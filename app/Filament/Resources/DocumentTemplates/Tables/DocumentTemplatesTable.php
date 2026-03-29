<?php

namespace App\Filament\Resources\DocumentTemplates\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'SK' => 'Surat Keputusan',
                        'Surat Tugas' => 'Surat Tugas',
                        'Undangan' => 'Undangan',
                        'Nota Dinas' => 'Nota Dinas',
                    ]),
            ])
            ->recordActions([
                 Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
