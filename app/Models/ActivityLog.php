<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',      // View, Download, Edit, Login
        'model_type',  // App\Models\Document
        'model_id',    // ID Dokumen yang diakses
        'ip_address',
        'description',
    ];

    /**
     * Relasi ke User yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi polimorfik (opsional) jika ingin menghubungkan ke berbagai model.
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
