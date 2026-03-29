<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'agency',
        'target_rank',
        'status',
        'expiry_date',
        'target_date',
        'criteria_progress',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'target_date' => 'date',
        'criteria_progress' => 'array',
    ];

    /**
     * Relasi ke Unit (Prodi/Fakultas)
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relasi ke Dokumen Bukti (Eviden)
     */
    public function evidences(): HasMany
    {
        return $this->hasMany(AccreditationEvidence::class);
    }

    /**
     * Helper: Mendapatkan daftar 9 Kriteria BAN-PT
     */
    public static function getCriteriaList(): array
    {
        return [
            'Kriteria 1' => 'Visi, Misi, Tujuan, dan Strategi',
            'Kriteria 2' => 'Tata Pamong, Tata Kelola, dan Kerjasama',
            'Kriteria 3' => 'Mahasiswa',
            'Kriteria 4' => 'Sumber Daya Manusia',
            'Kriteria 5' => 'Keuangan, Sarana, dan Prasarana',
            'Kriteria 6' => 'Pendidikan',
            'Kriteria 7' => 'Penelitian',
            'Kriteria 8' => 'Pengabdian kepada Masyarakat',
            'Kriteria 9' => 'Luaran dan Capaian Tridharma',
        ];
    }
}