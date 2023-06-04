<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\SendNotification;

use Symfony\Component\Uid\Uuid;

final readonly class SendNotificationCommand
{
    public function __construct(private Uuid $notificationId)
    {
    }

    public function getNotificationId(): Uuid
    {
        return $this->notificationId;
    }
}
