<?php

namespace App\Filament\Resources\AcademicEvents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcademicEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kegiatan')
                    ->description('Masukkan informasi jadwal penting akademik.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->placeholder('Contoh: Batas Akhir KRS Semester Ganjil')
                            ->maxLength(255),

                        Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->native(false)
                                ->displayFormat('d M Y'),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->native(false)
                                ->displayFormat('d M Y')
                                ->helperText('Kosongkan jika kegiatan hanya berlangsung satu hari.'),
                        ]),

                        Select::make('color')
                            ->label('Prioritas / Warna Label')
                            ->options([
                                'primary' => 'Normal (Biru)',
                                'success' => 'Selesai (Hijau)',
                                'warning' => 'Penting (Kuning)',
                                'danger' => 'Kritis (Merah)',
                            ])
                            ->default('primary')
                            ->required()
                            ->native(false),

                        Textarea::make('description')
                            ->label('Keterangan Tambahan')
                            ->rows(3)
                            ->placeholder('Tambahkan detail atau catatan khusus jika ada...'),
                    ]),
            ]);
    }
}
