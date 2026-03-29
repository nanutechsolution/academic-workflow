<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\ActivityLog;
use App\Models\Document;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- Section Informasi Utama (Kode Anda) ---
                Section::make('Informasi Utama')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('title')
                                ->label('Judul Dokumen')
                                ->weight(FontWeight::Bold),

                            TextEntry::make('version')
                                ->label('Versi')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('description')
                                ->label('Keterangan')
                                ->placeholder('Tidak ada deskripsi')
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->afterStateHydrated(function (Document $record) {
                        ActivityLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'View',
                            'model_type' => get_class($record),
                            'model_id' => $record->id,
                            'ip_address' => request()->ip(),
                            'description' => "Melihat rincian dokumen: {$record->title} (No: {$record->document_number})",
                        ]);
                    }),

                // --- Section Metadata (Kode Anda) ---
                Section::make('Metadata & Berkas')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('file_path')
                                ->label('Berkas PDF')
                                ->hintAction(
                                    Action::make('preview_infolist')
                                        ->label('Preview Cepat')
                                        ->icon('heroicon-m-eye')
                                        ->color('success')
                                        ->modalContent(fn($record) => view('components.pdf-preview', [
                                            'url' => \Illuminate\Support\Facades\Storage::url($record->file_path),
                                        ]))
                                        ->modalWidth('6xl')
                                        ->modalSubmitAction(false)
                                         ->after(function (Document $record) {
                                            ActivityLog::create([
                                                'user_id' => auth()->id(),
                                                'action' => 'Preview',
                                                'model_type' => get_class($record),
                                                'model_id' => $record->id,
                                                'ip_address' => request()->ip(),
                                                'description' => "Membuka preview PDF dokumen: {$record->title}",
                                            ]);
                                        })
                                ),
                            TextEntry::make('creator.name')
                                ->label('Dibuat Oleh')
                                ->icon('heroicon-o-user'),

                            IconEntry::make('is_published')
                                ->label('Status Publikasi')
                                ->boolean(),
                        ]),
                    ])->collapsible(),

                // --- TAMBAHKAN SECTION DISPOSISI DI SINI ---
                Section::make('Riwayat Instruksi Disposisi')
                    ->description('Daftar instruksi/disposisi yang menyertai dokumen ini.')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        RepeatableEntry::make('dispositions') // Pastikan relasi 'dispositions' ada di model Document
                            ->label('')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextEntry::make('fromUser.name')
                                        ->label('Dari')
                                        ->weight(FontWeight::Bold)
                                        ->color('primary'),

                                    TextEntry::make('toUser.name')
                                        ->label('Ditujukan Ke')
                                        ->icon('heroicon-m-arrow-right-circle'),

                                    TextEntry::make('created_at')
                                        ->label('Waktu Instruksi')
                                        ->dateTime('d M Y, H:i')
                                        ->color('gray'),
                                ]),

                                TextEntry::make('content')
                                    ->label('Isi Instruksi')
                                    ->prose() // Membuat teks lebih enak dibaca (paragraf)
                                    ->columnSpanFull(),

                                TextEntry::make('due_date')
                                    ->label('Deadline Tindak Lanjut')
                                    ->date('d M Y')
                                    ->badge()
                                    ->color('danger')
                                    ->visible(fn($state) => $state !== null), // Hanya muncul jika ada deadline
                            ])
                            ->grid(1) // Tampilkan per baris
                    ])
                    ->collapsible(),
            ]);
    }
}
