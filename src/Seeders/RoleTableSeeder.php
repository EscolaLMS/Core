<?php

namespace EscolaLms\Core\Seeders;

use EscolaLms\Core\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $adminPermissions = [
            'access dashboard',
            'user manage',
        ];
        $instructorPermissions = [
            'access dashboard',
        ];
        foreach ($instructorPermissions + $adminPermissions as $permission) {
            $this->createPermissionWithName($permission);
        }

        Role::findOrCreate(UserRole::STUDENT, 'api');

        $instructor = Role::findOrCreate(UserRole::TUTOR, 'api');
        $instructor->givePermissionTo($instructorPermissions);
        $admin = Role::findOrCreate(UserRole::ADMIN, 'api');
        $admin->givePermissionTo($adminPermissions);
    }

    public function createPermissionWithName(string $name)
    {
        return Permission::findOrCreate($name, 'api');
    }
}
