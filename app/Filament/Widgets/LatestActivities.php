<?php

namespace App\Filament\Widgets;

use App\Models\DocumentLog;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestActivities extends TableWidget
{
    protected static ?string $heading = 'Aktivitas Terakhir';
    protected static ?int $sort = 3; // Muncul paling bawah
    protected int | string | array $columnSpan = 'full'; // Memenuhi lebar layar
    public function table(Table $table): Table
    {
        return $table
            ->query(DocumentLog::latest()->limit(5))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('H:i:s'),
                TextColumn::make('user.name')
                    ->label('User'),
                TextColumn::make('action')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'gray',
                        'sent' => 'info',
                        'acknowledged' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('document.title')
                    ->label('Dokumen')
                    ->limit(30),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
