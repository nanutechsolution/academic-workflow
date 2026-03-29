<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentNumberLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'document_category_id',
        'formatted_number',
        'sequence_number',
        'user_id',
        'subject',
    ];

    /**
     * Relasi ke dokumen asli.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relasi ke kategori yang digunakan.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /**
     * Relasi ke admin/user yang mengeluarkan nomor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
