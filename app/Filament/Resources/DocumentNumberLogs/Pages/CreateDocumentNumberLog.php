<?php

namespace App\Filament\Resources\DocumentNumberLogs\Pages;

use App\Filament\Resources\DocumentNumberLogs\DocumentNumberLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentNumberLog extends CreateRecord
{
    protected static string $resource = DocumentNumberLogResource::class;

    // disable create page access since this resource is meant for logging only
    public static function canCreate(): bool
    {
        return false;
    }
}
