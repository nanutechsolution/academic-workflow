<?php

namespace App\Filament\Resources\DocumentNumberLogs;

use App\Enums\NavigationGroupEnum;
use App\Filament\Resources\DocumentNumberLogs\Pages\ListDocumentNumberLogs;
use App\Filament\Resources\DocumentNumberLogs\Tables\DocumentNumberLogsTable;
use App\Models\DocumentNumberLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DocumentNumberLogResource extends Resource
{
    protected static ?string $model = DocumentNumberLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Agenda Penomoran';
    protected static string|UnitEnum|null $navigationGroup =  NavigationGroupEnum::MASTER_DATA->value;
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'formatted_number';

    /**
     * Menonaktifkan tombol "Create" dan akses ke halaman pembuatan.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Menonaktifkan tombol "Edit" dan akses ke halaman penyuntingan.
     */
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    /**
     * Menonaktifkan fitur hapus agar log penomoran tetap utuh (Audit Trail).
     */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        // Pastikan di dalam OrganizationsTable hanya ada ViewAction::make()
        return DocumentNumberLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentNumberLogs::route('/'),
        ];
    }
}