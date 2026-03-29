<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Models\Document;
use App\Services\DocumentDistributionService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('version')->badge(),
                TextColumn::make('creator.name')->label('Pengirim'),
                TextColumn::make('created_at')->dateTime()->sortable(),

                // Menampilkan status khusus untuk user penerima
                TextColumn::make('status_inbox')
                    ->label('Status')
                    ->getStateUsing(function (Document $record) {
                        $user = auth()->user();
                        if ($user->hasRole('warek') || $user->hasRole('super_admin')) {
                            return 'Sent';
                        }
                        $flow = $record->flows()->where('to_org_id', $user->organization_id)->first();
                        return $flow ? ucfirst($flow->status) : '-';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Sent' => 'gray',
                        'Read' => 'warning',
                        'Acknowledged' => 'success',
                        default => 'gray',
                    }),

            ])
            ->filters([
                TernaryFilter::make('is_archived')
                    ->label('Tampilkan Arsip')
                    ->placeholder('Dokumen Aktif')
                    ->trueLabel('Hanya Arsip')
                    ->falseLabel('Hanya Aktif'),

                // Action untuk memindahkan ke Arsip

            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->modalHeading(fn($record) => "Preview: " . $record->title)
                        ->modalWidth('6xl')
                        ->modalSubmitAction(false)
                        ->modalContent(function ($record) {
                            $media = $record->getFirstMedia('document_files');
                            if (!$media) {
                                return view('components.pdf-error', ['message' => 'File tidak ditemukan.']);
                            }

                            return view('components.pdf-preview', [
                                'url' => $media->getUrl(),
                            ]);
                        }),
                    ViewAction::make()->icon('heroicon-m-document-text')->color('primary'),
                    EditAction::make()->icon('heroicon-m-pencil')->color('secondary'),
                    Action::make('download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn($record) => storage_path('app/public/' . $record->file_path))
                        ->openUrlInNewTab(),

                    // Tombol Acknowledge (Hanya muncul jika belum acknowledged dan user bukan warek)
                    Action::make('acknowledge')
                        ->label('Acknowledge')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(function ($record) {
                            // Pastikan record memiliki method yang dibutuhkan
                            if (! $record instanceof \App\Models\Document) {
                                return false;
                            }
                            $user = auth()->user();
                            if ($user->hasRole('warek') || $user->hasRole('super_admin')) return false;

                            $flow = $record->flows()->where('to_org_id', $user->organization_id)->first();
                            return $flow && $flow->status !== 'acknowledged';
                        })
                        ->action(function (Document $record) {
                            $user = auth()->user();
                            $service = new DocumentDistributionService();
                            $service->acknowledge($record, $user->id, $user->organization_id);
                        }),
                    Action::make('disposisi')
                        ->label('Disposisikan')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('warning')
                        ->form([
                            Select::make('to_user_id')
                                ->label('Teruskan Ke (Pejabat/Unit)')
                                ->options(fn() => \App\Models\User::where('id', '!=', auth()->id())->pluck('name', 'id'))
                                ->searchable()
                                ->required(),

                            Textarea::make('content')
                                ->label('Instruksi / Catatan Disposisi')
                                ->required()
                                ->placeholder('Contoh: Tolong segera buatkan draf laporannya...'),

                            DatePicker::make('due_date')
                                ->label('Batas Waktu Tindak Lanjut')
                                ->native(false),
                        ])
                        ->action(function (array $data, $record): void {
                            // Simpan ke tabel disposisi
                            $record->dispositions()->create([
                                'from_user_id' => auth()->id(),
                                'to_user_id' => $data['to_user_id'],
                                'content' => $data['content'],
                                'due_date' => $data['due_date'],
                            ]);

                            // Opsional: Update status alur dokumen menjadi 'disposed'
                            Notification::make()
                                ->title('Disposisi Terkirim')
                                ->success()
                                ->send();
                        }),
                    Action::make('archive')
                        ->label('Arsip')
                        ->icon('heroicon-o-archive-box')
                        ->color('gray')
                        ->hidden(fn($record) => $record->is_archived) // Sembunyikan jika sudah diarsip
                        ->action(function ($record) {
                            $record->update(['is_archived' => true]);

                            Notification::make()
                                ->title('Dokumen Berhasil Diarsip')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                ])->color('primary'),


            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        $query = Document::query();
        $user = auth()->user();

        // Warek atau Super Admin melihat dokumen yang mereka buat
        if ($user->hasRole('warek') || $user->hasRole('super_admin')) {
            return $query->where('created_by', $user->id);
        }

        // Dekan, Kaprodi, Staff hanya melihat dokumen yang masuk ke Inbox org mereka
        return $query->whereHas('flows', function ($q) use ($user) {
            $q->where('to_org_id', $user->organization_id);
        });
    }
}
