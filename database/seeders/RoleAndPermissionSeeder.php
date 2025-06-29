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
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminApi = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $viewerApi = Role::create(['name' => 'viewer', 'guard_name' => 'api']);

        $allApiPermissions = Permission::where('guard_name', 'api')->get();
        $adminApi->syncPermissions($allApiPermissions);

        $viewerApiPermissions = Permission::where('guard_name', 'api')
            ->whereIn('name', ['view_competitions', 'view_teams', 'view_players'])
            ->get();
        $viewerApi->syncPermissions($viewerApiPermissions);
    }
}
