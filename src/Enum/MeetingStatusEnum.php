<?php

declare(strict_types=1);

namespace App\Enum;

enum MeetingStatusEnum: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case CANCELED = 'CANCELED';
}