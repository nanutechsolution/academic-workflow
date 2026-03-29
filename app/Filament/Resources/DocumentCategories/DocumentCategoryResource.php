<?php

namespace App\Filament\Resources\DocumentCategories;

use App\Enums\NavigationGroupEnum;
use App\Filament\Resources\DocumentCategories\Pages\CreateDocumentCategory;
use App\Filament\Resources\DocumentCategories\Pages\EditDocumentCategory;
use App\Filament\Resources\DocumentCategories\Pages\ListDocumentCategories;
use App\Filament\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use App\Filament\Resources\DocumentCategories\Tables\DocumentCategoriesTable;
use App\Models\DocumentCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|UnitEnum|null  $navigationGroup = NavigationGroupEnum::MASTER_DATA->value;
    protected static ?string $navigationLabel = 'Kategori Penomoran';
    // sort 2
    protected static ?int $navigationSort = 2;
    public static function form(Schema $schema): Schema
    {
        return DocumentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentCategoriesTable::configure($table);
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
            'index' => ListDocumentCategories::route('/'),
            'create' => CreateDocumentCategory::route('/create'),
            'edit' => EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
