<?php

namespace App\Filament\Resources\Accreditations\Tables;

use App\Models\Accreditation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Tapp\FilamentProgressBarColumn\Tables\Columns\ProgressBarColumn;

class AccreditationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization.name')
                    ->label('Unit/Prodi')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama Akreditasi')
                    ->description(fn($record) => "Lembaga: {$record->agency}"),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Persiapan' => 'gray',
                        'Submit SAPTO' => 'info',
                        'Visitasi' => 'warning',
                        'Selesai' => 'success',
                        default => 'primary',
                    }),

                TextColumn::make('target_rank')
                    ->label('Target')
                    ->weight('bold'),

                TextColumn::make('target_date')
                    ->label('Target Submit')
                    ->date('M Y')
                    ->sortable(),

               ProgressBarColumn::make('total_progress')
                    ->label('Progress Borang')
                    ->getStateUsing(function ($record) {
                        if (!$record->criteria_progress) return 0;
                        $total = array_sum($record->criteria_progress);
                        return round($total / 9, 2); 
                    })
                    ->maxValue(100)
                    ->lowThreshold(30)
                    ->dangerColor('#dc2626')
                    ->warningColor('#f97316')
                    ->successColor('#16a34a')
                    // Mengubah label agar tidak muncul "in stock"
                    ->dangerLabel(fn ($state) => "{$state}% Kritis")
                    ->warningLabel(fn ($state) => "{$state}% Proses")
                    ->successLabel(fn ($state) => "{$state}% Siap"),
            ])
            ->filters([
                SelectFilter::make('status'),
                SelectFilter::make('organization_id')
                    ->label('Unit')
                    ->relationship('organization', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
