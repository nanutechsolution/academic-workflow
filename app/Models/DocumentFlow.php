<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentFlow extends Model
{
    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'to_org_id');
    }

    public function fromOrganization()
    {
        return $this->belongsTo(Organization::class, 'from_org_id');
    }

    public function toOrganization()
    {
        return $this->belongsTo(Organization::class, 'to_org_id');
    }
}
