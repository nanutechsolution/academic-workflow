<?php

namespace Database\Seeders;

use App\Models\AcademicEvent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AcademicEventSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi jadwal akademik UNMARIS.
     */
    public function run(): void
    {
        $year = date('Y');

        $events = [
            [
                'title' => 'Pendaftaran Mahasiswa Baru (Gelombang 1)',
                'start_date' => Carbon::create($year, 3, 1),
                'end_date' => Carbon::create($year, 5, 31),
                'color' => 'primary',
                'description' => 'Penerimaan mahasiswa baru jalur reguler gelombang pertama.',
            ],
            [
                'title' => 'Batas Akhir Pengisian KRS Semester Ganjil',
                'start_date' => Carbon::create($year, 8, 15),
                'end_date' => Carbon::create($year, 8, 30),
                'color' => 'danger',
                'description' => 'Mahasiswa wajib mengisi KRS melalui sistem SIAKAD. Keterlambatan akan dikenakan denda.',
            ],
            [
                'title' => 'Rapat Senat Terbuka (Wisuda Angkatan 2026)',
                'start_date' => Carbon::create($year, 10, 10),
                'end_date' => Carbon::create($year, 10, 10),
                'color' => 'success',
                'description' => 'Pelaksanaan wisuda sarjana di Aula Utama UNMARIS.',
            ],
            [
                'title' => 'Ujian Tengah Semester (UTS)',
                'start_date' => Carbon::create($year, 11, 1),
                'end_date' => Carbon::create($year, 11, 14),
                'color' => 'warning',
                'description' => 'Pekan UTS untuk seluruh fakultas. Mahasiswa wajib membawa kartu ujian.',
            ],
            [
                'title' => 'Dies Natalis UNMARIS',
                'start_date' => Carbon::create($year, 5, 20),
                'end_date' => Carbon::create($year, 5, 20),
                'color' => 'primary',
                'description' => 'Perayaan hari ulang tahun Universitas Stella Maris Sumba.',
            ],
        ];

        foreach ($events as $event) {
            AcademicEvent::updateOrCreate(
                ['title' => $event['title'], 'start_date' => $event['start_date']],
                $event
            );
        }

        $this->command->info("✅ AcademicEventSeeder: Jadwal kegiatan akademik berhasil dibuat.");
    }
}