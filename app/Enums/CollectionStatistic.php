<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum CollectionStatistic:string
{
    use EnumTrait;
    case CUSTOMER = 'customer';
    case RESERVATION = 'reservation';
}
