<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Organization;
use App\Services\DocumentDistributionService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen')->schema([
                    // --- Fitur Agenda Penomoran Otomatis ---
                    Select::make('document_category_id')
                        ->label('Kategori Surat')
                        ->options(DocumentCategory::pluck('name', 'id'))
                        ->reactive() // Memicu perubahan state secara real-time
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                $category = DocumentCategory::find($state);
                                // Memanggil fungsi generate dari model DocumentCategory
                                $set('document_number', $category->generateFormattedNumber());
                            } else {
                                $set('document_number', null);
                            }
                        })
                        ->required()
                        ->placeholder('Pilih kategori untuk mendapatkan nomor...'),

                    TextInput::make('document_number')
                        ->label('Nomor Surat (Otomatis)')
                        ->helperText('Nomor ini dihasilkan otomatis berdasarkan pola kategori yang dipilih.')
                        ->readOnly() // Mencegah perubahan manual agar nomor tetap konsisten
                        ->placeholder('Akan terisi otomatis...')
                        ->required(),
                    // --- Informasi Dasar Dokumen ---
                    TextInput::make('title')
                        ->label('Judul Dokumen')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Keterangan / Perihal Ringkas')
                        ->maxLength(65535),

                    SpatieMediaLibraryFileUpload::make('file')
                        ->label('Berkas Dokumen (PDF)')
                        ->collection('document_files')
                        ->required()
                        ->acceptedFileTypes(['application/pdf'])
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),

                    Hidden::make('created_by')
                        ->default(fn() => auth()->id()),
                ])->columns(2), // Mengubah menjadi 2 kolom agar tampilan lebih seimbang

                Section::make('Distribusi Dokumen')->schema([
                    Select::make('target_organizations')
                        ->label('Kirim ke Organisasi')
                        ->multiple()
                        ->options(Organization::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->dehydrated(false)
                        ->afterStateHydrated(function (Select $component, Document $record = null) {
                            if ($record) {
                                $targetIds = $record->targets()->pluck('target_id')->toArray();
                                $component->state($targetIds);
                            }
                        })
                        ->saveRelationshipsUsing(function (Document $record, $state) {
                            if (!empty($state)) {
                                $service = new DocumentDistributionService();
                                $service->distribute($record, $state);
                            }
                        }),
                ])
                ->visible(fn() => auth()->user()->hasRole('warek') || auth()->user()->hasRole('super_admin')),
            ]);
    }
}