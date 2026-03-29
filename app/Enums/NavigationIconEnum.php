<?php

namespace App\Enums;

use Filament\Support\Icons\Heroicon;

enum NavigationIconEnum: string
{
    // Master Data
    case ORGANIZATION = Heroicon::OutlinedBuildingOffice2;
    case CATEGORY = Heroicon::OutlinedTag;
    case TEMPLATE = Heroicon::OutlinedDocumentDuplicate;
    case NUMBER_LOG = Heroicon::OutlinedBookOpen;

    // Akademik
    case ACADEMIC_EVENT = Heroicon::OutlinedCalendarDays;

    // Administrasi
    case DOCUMENT = Heroicon::OutlinedDocumentText;
    case DISPOSITION = Heroicon::OutlinedPaperAirplane;

    // Keamanan & Sistem
    case ACTIVITY_LOG = Heroicon::OutlinedShieldCheck;
    case USER_MANAGEMENT = Heroicon::OutlinedUsers;
    case ROLE_PERMISSION = Heroicon::OutlinedKey;

    /**
     * Mendapatkan string ikon heroicon.
     */
    public function getIcon(): string
    {
        return $this->value;
    }
}