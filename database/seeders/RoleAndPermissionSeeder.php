<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            'view_competitions',
            'view_details_competitions',
            'view_teams',
            'view_details_teams',
            'view_players',
            'view_details_players',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin = Role::create(['name' => 'admin']);
        $viewer = Role::create(['name' => 'viewer']);

        $allPermissions = Permission::all();
        $admin->syncPermissions($allPermissions);

        $viewerPermissions = [
            'view_competitions',
            'view_teams',
            'view_players',
        ];

        $permissions = Permission::whereIn('name', $viewerPermissions)->get();
        $viewer->syncPermissions($permissions);
    }
}
