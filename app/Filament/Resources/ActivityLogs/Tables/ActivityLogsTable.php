<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'View' => 'info',
                        'Download' => 'success',
                        'Create' => 'primary',
                        'Update' => 'warning',
                        'Delete' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('model_type')
                    ->label('Target Objek')
                    ->formatStateUsing(fn($state) => str_replace('App\Models\\', '', $state)),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->fontFamily('mono'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('action')
                    ->options([
                        'View' => 'Lihat',
                        'Download' => 'Unduh',
                        'Create' => 'Buat Baru',
                        'Update' => 'Ubah',
                        'Delete' => 'Hapus',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
