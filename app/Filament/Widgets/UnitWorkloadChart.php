<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UnitWorkloadChart extends ChartWidget
{
    protected  ?string $heading = 'Beban Kerja Unit (Bulan Ini)';
    
    // Menentukan urutan tampilan di Dashboard
    
    protected  string $color = 'info';

    /**
     * Mengambil data statistik distribusi dokumen per organisasi/unit.
     */
    protected function getData(): array
    {
        $now = Carbon::now();
        
        // Mengambil jumlah dokumen yang diterima setiap unit pada bulan dan tahun ini
        $data = DB::table('document_targets')
            ->join('documents', 'document_targets.document_id', '=', 'documents.id')
            ->join('organizations', 'document_targets.target_id', '=', 'organizations.id')
            ->select('organizations.name', DB::raw('count(document_targets.id) as total_docs'))
            ->whereMonth('documents.created_at', $now->month)
            ->whereYear('documents.created_at', $now->year)
            ->groupBy('organizations.name')
            ->orderBy('total_docs', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dokumen Diterima',
                    'data' => $data->pluck('total_docs')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', // Biru (Primary)
                        '#10b981', // Hijau (Success)
                        '#f59e0b', // Kuning (Warning)
                        '#ef4444', // Merah (Danger)
                        '#8b5cf6', // Ungu
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Memastikan hanya Warek 1 atau Super Admin yang dapat melihat widget ini,
     * selaras dengan kebijakan akses dokumen yang telah kita atur.
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('warek') || auth()->user()->hasRole('super_admin');
    }
}