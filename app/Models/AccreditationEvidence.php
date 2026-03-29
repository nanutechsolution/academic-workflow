<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AccreditationEvidence extends Model
{
    protected $table = 'accreditation_evidences';
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'criterion',
        'title',
        'file_path',
        'uploaded_by',
    ];

    /**
     * Relasi ke data induk Akreditasi.
     */
    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    /**
     * Relasi ke User yang mengunggah dokumen.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Aksesor untuk mendapatkan URL file yang bisa diakses.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Mendapatkan label kriteria yang lebih manusiawi.
     */
    public function getCriterionLabelAttribute(): string
    {
        $list = Accreditation::getCriteriaList();
        return $list[$this->criterion] ?? $this->criterion;
    }
}