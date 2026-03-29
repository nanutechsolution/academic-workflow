<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroupEnum: string implements HasLabel
{
    case MASTER_DATA = 'Master Data';
    case ACADEMIC = 'Akademik';
    case ADMINISTRATION = 'Administrasi Surat';
    case SECURITY = 'Keamanan & Sistem';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
