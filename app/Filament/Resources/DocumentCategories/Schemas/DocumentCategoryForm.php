<?php

namespace App\Filament\Resources\DocumentCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pengaturan Format Nomor')
                    ->description('Tentukan bagaimana nomor surat akan disusun secara otomatis.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->placeholder('Contoh: Surat Keputusan (SK)'),

                        TextInput::make('code')
                            ->label('Kode Singkatan')
                            ->required()
                            ->placeholder('Contoh: SK'),

                        TextInput::make('pattern')
                            ->label('Pola Penomoran (Pattern)')
                            ->required()
                            ->default('{no}/UNMARIS/W1/{code}/{roman_month}/{year}')
                            ->helperText('Gunakan placeholder: {no}, {code}, {month}, {roman_month}, {year}'),

                        TextInput::make('next_no')
                            ->label('Nomor Berikutnya')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
