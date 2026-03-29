<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             Section::make('Informasi Unit Organisasi')
                    ->description('Tentukan struktur hierarki kampus di sini.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Unit/Organisasi')
                                ->required()
                                ->placeholder('Contoh: Fakultas Teknologi Informasi')
                                ->columnSpan(1),

                            Select::make('type')
                                ->label('Jenis Unit')
                                ->options([
                                    'university' => 'Universitas',
                                    'faculty' => 'Fakultas',
                                    'department' => 'Program Studi (Prodi)',
                                    'unit' => 'Unit Pelaksana/Lembaga',
                                ])
                                ->required()
                                ->columnSpan(1),
                        ]),

                        Select::make('parent_id')
                            ->label('Induk Organisasi (Parent)')
                            ->relationship('parent', 'name') // Pastikan ada relasi 'parent' di model Organization
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih induk jika ini adalah Prodi atau Fakultas')
                            ->helperText('Contoh: Jika membuat Prodi SI, pilih induknya Fakultas TI.')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
