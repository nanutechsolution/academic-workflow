<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'color',       // primary, danger, warning, success
        'description',
    ];

    /**
     * Casting atribut agar otomatis menjadi objek Carbon (tanggal).
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
