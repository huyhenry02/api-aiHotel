<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum ReservationStatusEnum: string
{
    use EnumTrait;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';

}
