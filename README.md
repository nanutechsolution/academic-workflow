
<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Framework-Laravel%2011-red?style=flat-square&logo=laravel" alt="Laravel Version">
    <img src="https://img.shields.io/badge/TALL%20Stack-Filament%20v3-blue?style=flat-square&logo=laravel" alt="Filament Version">
    <img src="https://img.shields.io/badge/PHP-8.2+-777bb4?style=flat-square&logo=php" alt="PHP Version">
    <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

## 🏛️ UNMARIS - Academic & Accreditation Management System

**UNMARIS** adalah sistem informasi manajemen terintegrasi yang dirancang khusus untuk mendukung operasional universitas, dengan fokus utama pada **Manajemen Akreditasi (Borang)** dan **Tata Kelola Administrasi Akademik**. 

Sistem ini dikembangkan untuk memudahkan **Wakil Rektor 1 (Bidang Akademik)** dalam melakukan monitoring kesiapan akreditasi di tingkat Program Studi maupun Institusi secara *real-time*.

---

### ✨ Fitur Unggulan

#### 1. 📊 Monitoring Akreditasi & Borang (9 Kriteria)
Sistem ini mengadopsi standar instrumen akreditasi **BAN-PT/LAM** terbaru:
- **Pelacakan Progres**: Monitoring kesiapan 9 kriteria menggunakan *interactive slider* dan *progress bar*.
- **Manajemen Eviden**: Unggah dan kelola dokumen bukti (PDF/Link) per kriteria secara terstruktur melalui fitur *Repeater*.
- **Reminder Expiry**: Notifikasi visual sebelum masa berlaku akreditasi program studi berakhir.

#### 2. ✉️ Digital Administration (E-Office)
- **Penomoran Otomatis**: Log penomoran surat otomatis berdasarkan kategori (SK, Surat Tugas, Surat Keluar, dll).
- **Alur Disposisi**: Distribusi instruksi dari pimpinan ke unit di bawahnya secara digital dengan status pelacakan.
- **Template Dokumen**: Standardisasi format kop surat dan dokumen resmi universitas.

#### 3. 👤 User & Role Management
- **RBAC**: Pengaturan hak akses spesifik (Warek, Dekan, Kaprodi, Staff) menggunakan **Spatie Role & Permissions**.
- **Audit Log**: Mencatat setiap aktivitas perubahan data untuk transparansi manajemen.

---

### 🚀 Teknologi yang Digunakan

*   **Backend**: Laravel 11
*   **Admin Panel**: Filament v3 (TALL Stack: Tailwind, Alpine.js, Laravel, Livewire)
*   **Database**: MySQL / PostgreSQL
*   **Components Utama**: 
    *   `tapp-network/filament-progress-bar-column`
    *   `spatie/laravel-permission`

---

### ⚙️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

**1. Clone Repositori**
```bash
git clone [https://github.com/username/unmaris-academic-management.git](https://github.com/username/unmaris-academic-management.git)
cd unmaris-academic-management
````

**2. Instalasi Dependensi**

```bash
composer install
npm install && npm run build
```

**3. Konfigurasi Environment**

```bash
cp .env.example .env
php artisan key:generate
```

> **Catatan**: Pastikan Anda telah membuat database dan menyesuaikan konfigurasi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.

**4. Migrasi Database & Seeder**

```bash
php artisan migrate --seed
```

**5. Link Storage**

```bash
php artisan storage:link
```

**6. Jalankan Aplikasi**

```bash
php artisan serve
```

Akses panel admin di: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

-----

### 📂 Struktur Folder Utama

  * `app/Filament/Resources/` : Tempat logika menu dan CRUD (Akreditasi, Dokumen, dll).
  * `app/Models/` : Definisi tabel dan relasi antar data.
  * `database/migrations/` : Struktur tabel database untuk akreditasi dan administrasi.
  * `storage/app/public/` : Tempat penyimpanan file dokumen bukti (eviden) dan surat.

-----

### 🛡️ Kontribusi

Jika Anda ingin berkontribusi dalam pengembangan sistem UNMARIS:

1.  **Fork** repositori ini.
2.  Buat **branch** fitur baru (`git checkout -b feature/NamaFitur`).
3.  **Commit** perubahan Anda (`git commit -m 'Menambah fitur X'`).
4.  **Push** ke branch tersebut (`git push origin feature/NamaFitur`).
5.  Buat **Pull Request**.

-----

### 📄 Lisensi

Sistem ini bersifat Open-Source di bawah lisensi **MIT license**.

\<p align="center"\>
Built with ❤️ for \<strong\>UNMARIS Academic Excellence\</strong\>
\</p\>
