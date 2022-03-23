<?php

namespace EscolaLms\Core\Tests\Features;

use EscolaLms\Core\Seeders\RoleTableSeeder;
use EscolaLms\Core\Tests\TestCase;
use Spatie\Permission\Models\Permission;

class RoleTableSeederTest extends TestCase
{
    public function testCreatePermissionWithName()
    {
        $seeder = new RoleTableSeeder();
        $response = $seeder->createPermissionWithName('testPermissionForTest');

        $permissionFromDb = Permission::query()->find($response->getKey());

        $this->assertEquals('testPermissionForTest', $response->name);
        $this->assertEquals($permissionFromDb->getKey(), $response->getKey());
    }
}
