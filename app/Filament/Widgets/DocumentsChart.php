<?php

namespace App\Filament\Widgets;

use App\Models\DocumentFlow;
use App\Models\Organization;
use Filament\Widgets\ChartWidget;

class DocumentsChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Dokumen Per Fakultas';
    protected string $color = 'info';
    protected ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 'full',
    ];
    // Properti ini memang statis di parent class, jadi tetap pakai static
    protected static ?int $sort = 3;

    // Properti ini non-statis di parent class
    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Organization::where('type', 'faculty')
            ->withCount(['incomingFlows' => function ($query) use ($activeFilter) { // Tambahkan 'use ($activeFilter)'
                // 1. Filter Status
                $query->whereIn('status', ['sent', 'acknowledged']);

                // 2. Filter Waktu (Hanya jalankan jika filter dipilih)
                if ($activeFilter === 'today') {
                    $query->whereDate('document_flows.created_at', today());
                } elseif ($activeFilter === 'week') {
                    $query->whereBetween('document_flows.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($activeFilter === 'month') {
                    $query->whereMonth('document_flows.created_at', now()->month)
                        ->whereYear('document_flows.created_at', now()->year);
                } elseif ($activeFilter === 'year') {
                    $query->whereYear('document_flows.created_at', now()->year);
                }
            }])
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dokumen',
                    'data' => $data->pluck('incoming_flows_count')->toArray(),
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
