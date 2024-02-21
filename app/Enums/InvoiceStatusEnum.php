<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum InvoiceStatusEnum: string
{
    use EnumTrait;

    case PAID = 'paid';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';

}
