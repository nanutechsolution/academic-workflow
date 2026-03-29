<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use App\Filament\Resources\Documents\DocumentResource;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
    protected static ?string $title = 'Audit Trail (Riwayat Aktivitas)';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $relatedResource = DocumentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Pelaku (User)')
                    ->searchable(),
                // Menampilkan nama organisasi dari user tersebut
                TextColumn::make('user.organization.name')
                    ->label('Organisasi')
                    ->placeholder('Tidak ada organisasi') // Membantu debug jika data null
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'gray',
                        'sent' => 'info',
                        'viewed' => 'warning',
                        'downloaded' => 'primary',
                        'acknowledged' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function canCreate(): bool
    {
        return false;
    }

    public function canEdit(Model $record): bool
    {
        return false;
    }

    public function canDelete(Model $record): bool
    {
        return false;
    }
}
