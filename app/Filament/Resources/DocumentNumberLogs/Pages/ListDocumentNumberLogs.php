<?php

namespace App\Filament\Resources\DocumentNumberLogs\Pages;

use App\Filament\Resources\DocumentNumberLogs\DocumentNumberLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentNumberLogs extends ListRecords
{
    protected static string $resource = DocumentNumberLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
