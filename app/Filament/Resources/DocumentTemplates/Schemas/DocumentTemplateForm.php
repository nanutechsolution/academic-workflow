<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make('Informasi Template')
                    ->description('Gunakan formulir ini untuk mengunggah berkas standar universitas.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Template')
                            ->required()
                            ->placeholder('Contoh: Template Kop Surat UNMARIS')
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'SK' => 'Surat Keputusan (SK)',
                                'Surat Tugas' => 'Surat Tugas',
                                'Undangan' => 'Surat Undangan',
                                'Nota Dinas' => 'Nota Dinas',
                                'Lainnya' => 'Lain-lain',
                            ])
                            ->required()
                            ->native(false),

                        FileUpload::make('file_path')
                            ->label('Berkas Template (DOCX/PDF)')
                            ->directory('document-templates')
                            ->acceptedFileTypes([
                                'application/pdf', 
                                'application/msword', 
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            ])
                            ->required()
                            ->preserveFilenames()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
