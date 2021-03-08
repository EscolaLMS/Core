<?php

namespace EscolaLms\Core\Enum;

use EscolaLms\Core\Enums\BasicEnum;

class UserRole extends BasicEnum
{
    const STUDENT = 'student';
    const INSTRUCTOR = 'instructor';
    const ADMIN = 'admin';
}
