<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->adminPermissions();
        $this->userPermissions();
    }

    private function adminPermissions()
    {
        $permissionsAdmin = [
                ['name' => 'auth.test', 'description' => 'Test access route'],
                ['name' => 'auth.test.admin', 'description' => 'Admin/Supplier test access route'],
                ['name' => 'auth.test.admin.user.', 'description' => 'Test access route'],
                ['name' => 'user.menu', 'description' => 'User menu'],
                ['name' => 'user.store', 'description' => 'User registration'],
                ['name' => 'user.index', 'description' => 'List users'],
                ['name' => 'user.update', 'description' => 'Update user'],
                ['name' => 'user.destroy', 'description' => 'Delete user'],
                ['name' => 'user.show', 'description' => 'View user by ID'],
                ['name' => 'permission.menu', 'description' => 'Permission menu'],
                ['name' => 'permission.store', 'description' => 'Permission registration'],
                ['name' => 'permission.update', 'description' => 'Update permission'],
                ['name' => 'permission.index', 'description' => 'View permission'],
                ['name' => 'permission.show', 'description' => 'View permission'],
                ['name' => 'permission.destroy', 'description' => 'Delete permission'],
                ['name' => 'profile.menu', 'description' => 'Profile menu'],
                ['name' => 'profile.store', 'description' => 'Profile registration'],
                ['name' => 'profile.update', 'description' => 'Update profile'],
                ['name' => 'profile.index', 'description' => 'View profile'],
                ['name' => 'profile.show', 'description' => 'View profile'],
                ['name' => 'profile.destroy', 'description' => 'Delete profile'],
                ['name' => 'profile.permissions', 'description' => 'Update permissions'],            
        ];

        foreach ($permissionsAdmin as $permission) {
            Profile::where('name', 'Admin')->first()->permissions()->syncWithoutDetaching(Permission::firstOrCreate($permission));
        }
    }

    private function userPermissions()
    {
        $permissionsUser = [
                ['name' => 'auth.test', 'description' => 'Test access route'],
                ['name' => 'auth.test.admin', 'description' => 'Admin/Supplier test access route'],
                ['name' => 'auth.test.admin.user', 'description' => 'Test access route'],
                ['name' => 'user.index', 'description' => 'List users'],
                ['name' => 'user.show', 'description' => 'View user by ID'],            
        ];

        foreach ($permissionsUser as $permission) {
            Profile::where('name', 'User')->first()->permissions()->syncWithoutDetaching(Permission::firstOrCreate($permission));
        }
    }
}
