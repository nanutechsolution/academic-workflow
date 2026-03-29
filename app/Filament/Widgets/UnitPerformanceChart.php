<?php


namespace App\Filament\Widgets;

use App\Models\Disposition;
use App\Models\Organization;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UnitPerformanceChart extends ChartWidget
{
    protected  ?string $heading = 'Leaderboard Responsivitas Unit (Rata-rata Menit)';
    protected  string $color = 'success';

    protected function getData(): array
    {
        // Menghitung rata-rata waktu respon (selisih created_at dan updated_at saat status selesai)
        $data = DB::table('dispositions')
            ->join('users', 'dispositions.to_user_id', '=', 'users.id')
            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
            ->select('organizations.name', DB::raw('AVG(TIMESTAMPDIFF(MINUTE, dispositions.created_at, dispositions.updated_at)) as avg_time'))
            ->where('dispositions.status', 'completed')
            ->groupBy('organizations.name')
            ->orderBy('avg_time', 'asc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Menit Penyelesaian',
                    'data' => $data->pluck('avg_time')->toArray(),
                    'backgroundColor' => '#34d399',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
