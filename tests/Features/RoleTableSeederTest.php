<?php

namespace EscolaLms\Core\Tests\Features;

use EscolaLms\Core\Seeders\RoleTableSeeder;
use EscolaLms\Core\Tests\TestCase;
use Spatie\Permission\Models\Permission;

class RoleTableSeederTest extends TestCase
{
    public function testCreatePermissionWithName(): void
    {
        $seeder = new RoleTableSeeder();
        $response = $seeder->createPermissionWithName('testPermissionForTest');

        $permissionFromDb = Permission::query()->find($response->getKey());

        $this->assertEquals('testPermissionForTest', $response->name);
        $this->assertEquals($permissionFromDb->getKey(), $response->getKey());
    }

    public function testRunSeeder(): void
    {
        Permission::query()->where([
            ['name', 'access dashboard'],
            ['guard_name', 'api']
        ])->delete();
        $count = Permission::query()->where([
            ['name', 'access dashboard'],
            ['guard_name', 'api']
        ])->count();
        $this->assertEquals(0, $count);

        $this->seed(RoleTableSeeder::class);

        $count = Permission::query()->where([
            ['name', 'access dashboard'],
            ['guard_name', 'api']
        ])->count();
        $this->assertEquals(1, $count);
    }
}
