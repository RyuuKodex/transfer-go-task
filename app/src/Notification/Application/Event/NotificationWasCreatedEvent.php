<?php

declare(strict_types=1);

namespace App\Notification\Application\Event;

use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

final class NotificationWasCreatedEvent extends Event
{
    public Uuid $notificationId;

    public function __construct(Uuid $notificationId)
    {
        $this->notificationId = $notificationId;
    }
}
