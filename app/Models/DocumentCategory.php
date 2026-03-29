<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pattern',
        'next_no',
    ];

    /**
     * Relasi ke log penomoran.
     */
    public function numberLogs(): HasMany
    {
        return $this->hasMany(DocumentNumberLog::class);
    }

    /**
     * Fungsi Inti: Menghasilkan nomor surat berikutnya berdasarkan pola (pattern).
     * Contoh Pola: {no}/UNMARIS/W1/{code}/{month}/{year}
     */
    public function generateFormattedNumber(): string
    {
        $no = str_pad($this->next_no, 3, '0', STR_PAD_LEFT);
        $month = date('m');
        $year = date('Y');
        $romanMonth = $this->getRomanMonth($month);

        // Mengganti placeholder dengan data dinamis
        return str_replace(
            ['{no}', '{code}', '{month}', '{roman_month}', '{year}'],
            [$no, $this->code, $month, $romanMonth, $year],
            $this->pattern
        );
    }

    /**
     * Konversi bulan ke angka romawi (opsional, sering digunakan di kampus).
     */
    private function getRomanMonth($month): string
    {
        $romans = [
            '01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV', 
            '05' => 'V', '06' => 'VI', '07' => 'VII', '08' => 'VIII', 
            '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII'
        ];
        return $romans[$month] ?? $month;
    }
}