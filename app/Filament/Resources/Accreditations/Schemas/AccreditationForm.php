<?php

namespace App\Filament\Resources\Accreditations\Schemas;

use App\Models\Accreditation;
use App\Models\Organization;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Range;

class AccreditationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akreditasi')
                    ->description('Detail pengajuan akreditasi program studi atau institusi.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengajuan')
                            ->required()
                            ->placeholder('Contoh: Akreditasi SI 2026')
                            ->columnSpanFull(),

                        Select::make('organization_id')
                            ->label('Unit / Prodi')
                            ->options(Organization::where('type', 'prodi')->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Select::make('agency')
                            ->label('Lembaga Akreditasi')
                            ->options([
                                'BAN-PT' => 'BAN-PT',
                                'LAM-INFOKOM' => 'LAM-INFOKOM',
                                'LAM-DIK' => 'LAM-DIK',
                                'LAM-PTKES' => 'LAM-PTKES',
                            ])
                            ->required(),

                        Select::make('target_rank')
                            ->label('Target Peringkat')
                            ->options([
                                'Unggul' => 'Unggul',
                                'Baik Sekali' => 'Baik Sekali',
                                'Baik' => 'Baik',
                                'Terakreditasi' => 'Terakreditasi',
                            ]),

                        Select::make('status')
                            ->label('Status Saat Ini')
                            ->options([
                                'Persiapan' => 'Persiapan Borang',
                                'Submit SAPTO' => 'Submit SAPTO/LAMS',
                                'Asesmen Kecukupan' => 'Asesmen Kecukupan (AK)',
                                'Visitasi' => 'Asesmen Lapangan (Visitasi)',
                                'Selesai' => 'Selesai (SK Terbit)',
                            ])
                            ->required(),

                        DatePicker::make('target_date')
                            ->label('Target Submit')
                            ->native(false),

                        DatePicker::make('expiry_date')
                            ->label('Berlaku Hingga')
                            ->native(false),
                    ])->columns(2),

                Section::make('Progress 9 Kriteria')
                    ->description('Pantau kesiapan dokumen borang untuk setiap kriteria.')
                    ->schema([
                        Grid::make(3)->schema(
                            collect(Accreditation::getCriteriaList())->map(function ($label, $key) {
                                return Slider::make("criteria_progress.{$key}")
                                    ->label($key . ': ' . $label)
                                    ->range(minValue: 0, maxValue: 100)
                                    ->step(1)
                                    ->rangePadding(10)
                                    ->default(0);
                            })->toArray()
                        ),
                    ]),

                Section::make('Dokumen Eviden (Borang)')
                    ->schema([
                        Repeater::make('evidences')
                            ->relationship('evidences')
                            ->schema([
                                Select::make('criterion')
                                    ->label('Kriteria')
                                    ->options(Accreditation::getCriteriaList())
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Nama Dokumen')
                                    ->required(),
                                FileUpload::make('file_path')
                                    ->label('Unggah PDF')
                                    ->directory('accreditation-evidences')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->required(),
                                Hidden::make('uploaded_by')
                                    ->default(auth()->id()),
                            ])
                            ->columns(3)
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
