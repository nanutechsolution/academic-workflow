<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $warek = Role::firstOrCreate(['name' => 'warek']);
        $dekan = Role::firstOrCreate(['name' => 'dekan']);
        $kaprodi = Role::firstOrCreate(['name' => 'kaprodi']);
        $staff = Role::firstOrCreate(['name' => 'staff']);

        // Ambil semua permission dari Shield
        $permissions = Permission::all();

        // Assign permission
        $superAdmin->syncPermissions($permissions); // full akses

        $warek->syncPermissions([
            'view_any_document',
            'create_document',
            'update_document',
        ]);

        $dekan->syncPermissions([
            'view_any_document',
        ]);

        $kaprodi->syncPermissions([
            'view_any_document',
        ]);

        $staff->syncPermissions([
            'view_any_document',
        ]);
    }
}