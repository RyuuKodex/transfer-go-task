<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Specification;

use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;

final readonly class CanSendNextNotificationSpecification implements CanSendNextNotificationSpecificationInterface
{
    public function __construct(private NotificationStoreInterface $notificationStore, private int $throttlingThreshold)
    {
    }

    public function canSend(Notification $notification): bool
    {
        $count = $this->notificationStore->countNotificationInLastHourByReceiver($notification->getReceiver());

        return $count < $this->throttlingThreshold;
    }
}
