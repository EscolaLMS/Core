<?php

namespace EscolaLms\Core\Tests\Enums;

use EscolaLms\Core\Enums\StatusEnum;
use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Tests\TestCase;

class BasicEnumTest extends TestCase
{
    public function testStatusEnum()
    {
        $this->assertEquals([0 => "INACTIVE",1 => "ACTIVE"], StatusEnum::getAssoc());
        $this->assertEquals(2, StatusEnum::getDetails()->count());
        $this->assertTrue(StatusEnum::isValidName("INACTIVE"));
        $this->assertFalse(StatusEnum::isValidName(0));
        $this->assertTrue(StatusEnum::isValidValue(1));
        $this->assertFalse(StatusEnum::isValidValue("ACTIVE"));
        $this->assertEquals("ACTIVE", StatusEnum::getName(1));
        $this->assertEquals(0, StatusEnum::getValue("INACTIVE"));
    }

    public function testUserRole()
    {
        $this->assertEquals(["student" => "STUDENT","tutor" => "TUTOR","admin" => "ADMIN"], UserRole::getAssoc());
        $this->assertEquals(3, UserRole::getDetails()->count());
        $this->assertTrue(UserRole::isValidName("STUDENT"));
        $this->assertFalse(UserRole::isValidName("abc"));
        $this->assertTrue(UserRole::isValidValue("tutor"));
        $this->assertFalse(UserRole::isValidValue("SuperAdmin"));
        $this->assertEquals("ADMIN", UserRole::getName("admin"));
        $this->assertEquals("student", UserRole::getValue("STUDENT"));
    }
}
