<?php

namespace App\Filament\Resources\DocumentNumberLogs\Pages;

use App\Filament\Resources\DocumentNumberLogs\DocumentNumberLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentNumberLog extends EditRecord
{
    protected static string $resource = DocumentNumberLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
