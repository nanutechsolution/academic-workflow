<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'category', // SK, Surat Tugas, Undangan
    ];
}
