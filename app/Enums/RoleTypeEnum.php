<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum RoleTypeEnum:string
{
    use EnumTrait;
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case CUSTOMER = 'customer';
}
