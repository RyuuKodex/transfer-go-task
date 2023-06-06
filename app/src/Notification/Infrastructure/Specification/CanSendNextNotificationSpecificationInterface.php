<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Specification;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;

interface CanSendNextNotificationSpecificationInterface
{
    public function canSend(Notification $notification): bool;
}
