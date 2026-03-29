<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentTarget;
use App\Models\DocumentFlow;
use App\Models\DocumentLog;
use App\Models\Organization;
use App\Models\User; // 🛠️ Tambahkan ini
use Filament\Actions\Action as ActionsAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification; // 🛠️ Tambahkan ini

class DocumentDistributionService
{
    public function distribute(Document $document, array $targetIds)
    {
        try {
            DB::transaction(function () use ($document, $targetIds) {
                $document->targets()->delete();
                $document->flows()->delete();
                $resolvedOrgIds = [];

                // 🛠️ Ambil organisasi pengirim
                $fromOrgId = $document->creator->organization_id;
                if (!$fromOrgId) {
                    $fromOrgId = Organization::where('type', 'university')->first()->id ?? 1;
                }

                foreach ($targetIds as $orgId) {
                    $org = Organization::find($orgId);
                    if (!$org) continue;

                    // 1. Simpan original target metadata
                    DocumentTarget::create([
                        'document_id' => $document->id,
                        'target_type' => 'organization',
                        'target_id'   => $org->id,
                    ]);

                    // 2. Resolve Hierarki
                    $resolvedOrgIds[] = $org->id;
                    if ($org->type === 'faculty') {
                        $prodiIds = $org->children()->pluck('id')->toArray();
                        $resolvedOrgIds = array_merge($resolvedOrgIds, $prodiIds);
                    }
                }

                $resolvedOrgIds = array_unique($resolvedOrgIds);

                // 3. Buat Document Flow
                $flows = [];
                foreach ($resolvedOrgIds as $toOrgId) {
                    $flows[] = [
                        'document_id' => $document->id,
                        'from_org_id' => $fromOrgId,
                        'to_org_id'   => $toOrgId,
                        'status'      => 'sent',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }

                if (!empty($flows)) {
                    DocumentFlow::insert($flows);
                }

                // 4. Catat ke Audit Log
                DocumentLog::create([
                    'document_id' => $document->id,
                    'user_id'     => $document->created_by,
                    'action'      => 'sent',
                ]);

                // 🔔 5. KIRIM NOTIFIKASI LONCENG
                // Cari semua user yang berada di organisasi tujuan
                $usersToNotify = User::whereIn('organization_id', $resolvedOrgIds)->get();

                if ($usersToNotify->count() > 0) {
                    Notification::make()
                        ->title('Dokumen Baru Diterima')
                        ->icon('heroicon-o-bell')
                        ->iconColor('success')
                        ->body("Anda menerima dokumen baru: **{$document->title}**")
                        ->actions([
                            ActionsAction::make('Lihat')
                                ->url(\App\Filament\Resources\Documents\DocumentResource::getUrl('view', ['record' => $document->id]))
                                ->button(),
                        ])
                        ->sendToDatabase($usersToNotify);
                }
            });
        } catch (\Exception $e) {
            Log::error('GAGAL DISTRIBUSI DOKUMEN: ' . $e->getMessage());
            throw $e;
        }
    }

    public function acknowledge(Document $document, int $userId, int $orgId)
    {
        DB::transaction(function () use ($document, $userId, $orgId) {
            DocumentFlow::where('document_id', $document->id)
                ->where('to_org_id', $orgId)
                ->update([
                    'status'  => 'acknowledged',
                    'read_at' => now(),
                ]);

            DocumentLog::create([
                'document_id' => $document->id,
                'user_id'     => $userId,
                'action'      => 'acknowledged',
            ]);

            // 🔔 Notifikasi balik ke pembuat dokumen (Warek/Admin) bahwa sudah di-ACK
            $creator = $document->creator;
            if ($creator) {
                Notification::make()
                    ->title('Dokumen Telah Di-Acknowledge')
                    ->body("Unit organisasi telah menerima dokumen: {$document->title}")
                    ->info()
                    ->sendToDatabase($creator);
            }
        });
    }
}
