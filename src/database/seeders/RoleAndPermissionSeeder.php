<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage customers',
            'manage leads',
            'manage deals',
            'manage tasks',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $agentRole = Role::firstOrCreate(['name' => 'Agent']);

        $adminRole->syncPermissions($permissions);

        $managerRole->syncPermissions([
            'view dashboard',
            'manage customers',
            'manage leads',
            'manage deals',
            'manage tasks',
            'view reports',
        ]);

        $agentRole->syncPermissions([
            'view dashboard',
            'manage leads',
            'manage deals',
            'manage tasks',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@primedesk.local'],
            [
                'name' => 'PrimeDesk Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles([$adminRole]);
    }
}
