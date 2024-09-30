<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $cashierRole = Role::create(['name' => 'Cashier']);

        // Create permissions
        $createPermission = Permission::create(['name' => 'create']);
        $editPermission = Permission::create(['name' => 'edit']);
        $deletePermission = Permission::create(['name' => 'delete']);

        // Assign permissions to roles
        $superAdminRole->givePermissionTo($createPermission);
        $superAdminRole->givePermissionTo($editPermission);
        $superAdminRole->givePermissionTo($deletePermission);

        $adminRole->givePermissionTo($createPermission);
        $adminRole->givePermissionTo($editPermission);
        $adminRole->givePermissionTo($deletePermission);

        $cashierRole->givePermissionTo($createPermission);

        // Assign roles to users (optional)
        $sa = User::find(1);
        $sa->assignRole($superAdminRole);

        $a = User::find(3);
        $a->assignRole($adminRole);

        $c = User::find(4);
        $c->assignRole($cashierRole);
    }
}

