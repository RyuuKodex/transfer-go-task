<?php

declare(strict_types=1);

namespace App\Notification\Domain\Enum;

enum NotificationStatus: string
{
    case Created = 'created';
}
