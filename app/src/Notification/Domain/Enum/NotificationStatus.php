<?php

declare(strict_types=1);

namespace App\Notification\Domain\Enum;

enum NotificationStatus: string
{
    case Created = 'created';
    case Sent = 'sent';
    case AllChannelsFailed = 'all_channels_failed';

    case NotSentDueToThrottling = 'not_set_due_to_throttling';
}
