<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $guarded = [];

    // Relasi ke Parent (misal: Prodi ke Fakultas)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    // Relasi ke Children (misal: Fakultas ke banyak Prodi)
    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke alur dokumen yang ditujukan ke organisasi ini.
     * Digunakan untuk menghitung statistik distribusi dokumen.
     */
    public function incomingFlows(): HasMany
    {
        // Parameter kedua 'to_org_id' adalah foreign key di tabel document_flows
        return $this->hasMany(DocumentFlow::class, 'to_org_id');
    }
}
