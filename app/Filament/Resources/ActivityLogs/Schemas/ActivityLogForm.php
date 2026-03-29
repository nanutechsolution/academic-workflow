<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextColumn::make('user.name')->label('User')->sortable(),
                TextColumn::make('action')->badge()->color(fn($state) => match ($state) {
                    'View' => 'info',
                    'Download' => 'success',
                    default => 'gray'
                }),
                TextColumn::make('model_type')->label('Target'),
                TextColumn::make('ip_address')->label('IP Address'),
                TextColumn::make('created_at')->label('Waktu')->dateTime(),
            ])->defaultSort('created_at', 'desc')
            ->columns(1);
    }
}
