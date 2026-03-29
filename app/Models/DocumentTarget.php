<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTarget extends Model
{
    protected $guarded = [];

    // 2. Relasi ke Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // 3. Relasi ke Organization (Target)
    public function targetOrganization()
    {
        return $this->belongsTo(Organization::class, 'target_id');
    }
}
