<?php

namespace App\Filament\Resources\Documents;

use App\Enums\NavigationGroupEnum;
use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Filament\Resources\Documents\Pages\ViewDocument;
use App\Filament\Resources\Documents\RelationManagers\LogsRelationManager;
use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\Documents\Schemas\DocumentInfolist;
use App\Filament\Resources\Documents\Tables\DocumentsTable;
use App\Models\Document;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Dokumen & Inbox';
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|UnitEnum|null $navigationGroup =  NavigationGroupEnum::ADMINISTRATION->value;
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Dokumen & Inbox';
    protected static ?string $pluralLabel = 'Dokumen & Inbox';
    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            LogsRelationManager::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return \App\Filament\Resources\Documents\Tables\DocumentsTable::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'view' => ViewDocument::route('/{record}'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
