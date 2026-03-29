<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function targets()
    {
        return $this->hasMany(DocumentTarget::class);
    }
    public function flows()
    {
        return $this->hasMany(DocumentFlow::class);
    }
    public function logs()
    {
        return $this->hasMany(DocumentLog::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('document_files')
            ->singleFile(); // Menimpa file lama jika upload baru
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class);
    }

    /**
     * Relasi ke Kategori Dokumen
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /**
     * Relasi ke Log Penomoran (Agenda)
     */
    public function numberLogs(): HasMany
    {
        return $this->hasMany(DocumentNumberLog::class);
    }

    /**
     * Boot function untuk menangani logika otomatis saat dokumen dibuat.
     */
    protected static function booted()
    {
        static::created(function ($document) {
            // Jika dokumen memiliki kategori, catat ke log dan naikkan nomor urut
            if ($document->document_category_id) {
                // 1. Simpan ke tabel DocumentNumberLog (Agenda)
                DocumentNumberLog::create([
                    'document_id' => $document->id,
                    'document_category_id' => $document->document_category_id,
                    'formatted_number' => $document->document_number,
                    'sequence_number' => $document->category->next_no, // Mengambil nomor urut saat ini
                    'user_id' => auth()->id() ?? $document->created_by,
                    'subject' => $document->title,
                ]);

                // 2. Naikkan nomor urut di tabel DocumentCategory untuk surat berikutnya
                $document->category->increment('next_no');
            }
        });
    }
}
