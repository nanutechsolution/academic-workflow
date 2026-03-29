<?php

namespace App\Filament\Resources\AcademicEvents;

use App\Enums\NavigationGroupEnum;
use App\Filament\Resources\AcademicEvents\Pages\CreateAcademicEvent;
use App\Filament\Resources\AcademicEvents\Pages\EditAcademicEvent;
use App\Filament\Resources\AcademicEvents\Pages\ListAcademicEvents;
use App\Filament\Resources\AcademicEvents\Schemas\AcademicEventForm;
use App\Filament\Resources\AcademicEvents\Tables\AcademicEventsTable;
use App\Models\AcademicEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AcademicEventResource extends Resource
{
    protected static ?string $model = AcademicEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|UnitEnum|null $navigationGroup = NavigationGroupEnum::ACADEMIC->value;
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'kegiatan-akademik';
    protected static ?string $label = 'Kegiatan Akademik';
    protected static ?string $pluralLabel = 'Kegiatan Akademik';
    public static function form(Schema $schema): Schema
    {
        return AcademicEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicEventsTable::configure($table);
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
            'index' => ListAcademicEvents::route('/'),
            'create' => CreateAcademicEvent::route('/create'),
            'edit' => EditAcademicEvent::route('/{record}/edit'),
        ];
    }
}
