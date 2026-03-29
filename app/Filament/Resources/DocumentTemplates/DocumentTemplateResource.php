<?php

namespace App\Filament\Resources\DocumentTemplates;

use App\Enums\NavigationGroupEnum;
use App\Filament\Resources\DocumentTemplates\Pages\CreateDocumentTemplate;
use App\Filament\Resources\DocumentTemplates\Pages\EditDocumentTemplate;
use App\Filament\Resources\DocumentTemplates\Pages\ListDocumentTemplates;
use App\Filament\Resources\DocumentTemplates\Schemas\DocumentTemplateForm;
use App\Filament\Resources\DocumentTemplates\Tables\DocumentTemplatesTable;
use App\Models\DocumentTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Static_;
use UnitEnum;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;
    protected static ?string $navigationLabel = 'Template Dokumen';
    protected static string|UnitEnum|null $navigationGroup =  NavigationGroupEnum::MASTER_DATA->value;
    // sort 3
    protected static ?int $navigationSort = 3;
    protected static ?string $breadcrumb = 'Template Dokumen';
    public static function form(Schema $schema): Schema
    {
        return DocumentTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentTemplates::route('/'),
            'create' => CreateDocumentTemplate::route('/create'),
            'edit' => EditDocumentTemplate::route('/{record}/edit'),
        ];
    }
}
