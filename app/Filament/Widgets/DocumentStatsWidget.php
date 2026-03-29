<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Models\DocumentFlow;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocumentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        // ✅ Cek role dengan Spatie
        if ($user->hasRole('warek')) {
            $totalSent = Document::where('created_by', $user->id)->count();

            $totalDistributions = DocumentFlow::whereHas('document', fn($q) => $q->where('created_by', $user->id))->count();

            $acknowledged = DocumentFlow::whereHas('document', fn($q) => $q->where('created_by', $user->id))
                ->where('status', 'acknowledged')
                ->count();

            return [
                Stat::make('Total Dokumen Dibuat', $totalSent)
                    ->description('Dokumen yang Anda unggah')
                    ->icon('heroicon-o-document-text')
                    ->color('primary'),
                Stat::make('Total Distribusi', $totalDistributions)
                    ->description('Ke berbagai Fakultas & Prodi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info'),
                Stat::make('Sudah Di-Acknowledge', $acknowledged)
                    ->description('Organisasi yang sudah membaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
            ];
        }

        if ($user->hasRole('dekan') || $user->hasRole('kaprodi') || $user->hasRole('staff')) {
            $orgId = $user->organization_id;

            $totalInbox = DocumentFlow::where('to_org_id', $orgId)->count();
            $unread = DocumentFlow::where('to_org_id', $orgId)->where('status', 'sent')->count();
            $acknowledgedInbox = DocumentFlow::where('to_org_id', $orgId)->where('status', 'acknowledged')->count();

            return [
                Stat::make('Total Inbox', $totalInbox)
                    ->description('Dokumen masuk ke organisasi Anda')
                    ->icon('heroicon-o-inbox')
                    ->color('primary'),
                Stat::make('Belum Direspon', $unread)
                    ->description('Segera baca dan acknowledge')
                    ->icon('heroicon-o-bell-alert')
                    ->color('danger'),
                Stat::make('Selesai Diproses', $acknowledgedInbox)
                    ->description('Dokumen sudah di-acknowledge')
                    ->icon('heroicon-o-check-badge')
                    ->color('success'),
            ];
        }

        // Default: kosong kalau role lain
        return [];
    }
}
