<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use App\Models\DocumentCategory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // ⚡ Reset cache permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =====================
        // 1️⃣ Buat Roles
        // =====================
        $roles = ['super_admin', 'warek', 'dekan', 'kaprodi', 'staff'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // =====================
        // 2️⃣ Buat Permissions
        // =====================
        $permissions = [
            'view_any_document',
            'create_document',
            'update_document',
            'delete_document',
            'acknowledge_document',
            'dispose_document', // Tambahan untuk fitur disposisi
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permission ke role
        Role::findByName('super_admin')->syncPermissions(Permission::all());

        Role::findByName('warek')->syncPermissions([
            'view_any_document',
            'create_document',
            'update_document',
            'dispose_document',
        ]);

        Role::findByName('dekan')->syncPermissions([
            'view_any_document',
            'acknowledge_document',
            'dispose_document',
        ]);

        Role::findByName('kaprodi')->syncPermissions([
            'view_any_document',
            'acknowledge_document',
        ]);

        Role::findByName('staff')->syncPermissions([
            'view_any_document',
        ]);

        // =====================
        // 3️⃣ Buat Organisasi
        // =====================
        $univ = Organization::firstOrCreate(
            ['name' => 'Universitas Stella Maris Sumba (UNMARIS)'],
            ['type' => 'university']
        );

        // Fakultas
        $ftik = Organization::firstOrCreate(
            ['name' => 'Fakultas Teknologi Informasi dan Komputer'],
            ['type' => 'faculty', 'parent_id' => $univ->id]
        );

        $fkip = Organization::firstOrCreate(
            ['name' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['type' => 'faculty', 'parent_id' => $univ->id]
        );

        // Prodi (Menggunakan type 'prodi' sesuai permintaan Anda)
        $prodiSI = Organization::firstOrCreate(
            ['name' => 'Sistem Informasi'],
            ['type' => 'prodi', 'parent_id' => $ftik->id]
        );

        $prodiTI = Organization::firstOrCreate(
            ['name' => 'Teknik Informatika'],
            ['type' => 'prodi', 'parent_id' => $ftik->id]
        );

        $prodiPGSD = Organization::firstOrCreate(
            ['name' => 'Pendidikan Guru Sekolah Dasar'],
            ['type' => 'prodi', 'parent_id' => $fkip->id]
        );

        // =====================
        // 4️⃣ Buat Kategori Dokumen (Agenda Penomoran)
        // =====================
        $categories = [
            [
                'name' => 'Surat Keputusan',
                'code' => 'SK',
                'pattern' => '{no}/UNMARIS/W1/{code}/{roman_month}/{year}',
            ],
            [
                'name' => 'Surat Tugas',
                'code' => 'ST',
                'pattern' => '{no}/UNMARIS/W1/{code}/{month}/{year}',
            ],
            [
                'name' => 'Surat Undangan',
                'code' => 'UND',
                'pattern' => '{no}/UNMARIS/W1/{code}/{roman_month}/{year}',
            ],
            [
                'name' => 'Nota Dinas',
                'code' => 'ND',
                'pattern' => '{no}/UNMARIS/W1/{code}/{month}/{year}',
            ],
        ];

        foreach ($categories as $cat) {
            DocumentCategory::firstOrCreate(
                ['code' => $cat['code']],
                [
                    'name' => $cat['name'],
                    'pattern' => $cat['pattern'],
                    'next_no' => 1,
                ]
            );
        }

        // =====================
        // 5️⃣ Buat Users & Assign Role
        // =====================
        $users = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@unmaris.ac.id',
                'password' => 'password',
                'role' => 'super_admin',
                'organization_id' => $univ->id,
            ],
            [
                'name' => 'Dr. Budi (Warek 1)',
                'email' => 'warek1@unmaris.ac.id',
                'password' => 'password',
                'role' => 'warek',
                'organization_id' => $univ->id,
            ],
            [
                'name' => 'Prof. Andi (Dekan FTIK)',
                'email' => 'dekan.ftik@unmaris.ac.id',
                'password' => 'password',
                'role' => 'dekan',
                'organization_id' => $ftik->id,
            ],
            [
                'name' => 'Siti, M.Kom (Kaprodi SI)',
                'email' => 'kaprodi.si@unmaris.ac.id',
                'password' => 'password',
                'role' => 'kaprodi',
                'organization_id' => $prodiSI->id,
            ],
            [
                'name' => 'Andi, M.Kom (Staff SI)',
                'email' => 'staff.si@unmaris.ac.id',
                'password' => 'password',
                'role' => 'staff',
                'organization_id' => $prodiSI->id,
            ],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'organization_id' => $u['organization_id'],
                ]
            );
            
            // Sync role agar tidak duplikat jika seeder dijalankan ulang
            $user->syncRoles([$u['role']]);
        }

        $this->command->info("✅ AcademicSeeder: Roles, Permissions, Organizations, Categories, and Users created successfully.");
    }
}