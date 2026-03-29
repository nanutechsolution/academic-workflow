<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposition extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Sesuaikan dengan kolom yang kita buat di migrasi sebelumnya.
     */
    protected $fillable = [
        'document_id',
        'from_user_id',
        'to_user_id',
        'content',
        'status',
        'due_date',
    ];

    /**
     * Relasi ke Dokumen
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relasi ke Pengirim (Pemberi Instruksi)
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Relasi ke Penerima (Pelaksana Instruksi)
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}