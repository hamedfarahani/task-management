<?php

namespace App\Enum;

Class RoleEnum
{

    const AREA = 'area';
    const AREA_USER = 'user';
    const AREA_ADMIN = 'admin';

    const SUPER_ADMIN = 'SUPERADMIN';
    const ADMIN = 'ADMIN';
    const OPERATOR = 'OPERATOR';
    const USER = 'USER';

    const AREA_TYPES = [
        self::AREA_ADMIN,
        self::AREA_USER,
    ];
}
