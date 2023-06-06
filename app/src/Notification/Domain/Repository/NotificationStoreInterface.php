<?php

declare(strict_types=1);

namespace App\Notification\Domain\Repository;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Symfony\Component\Uid\Uuid;

interface NotificationStoreInterface
{
    public function store(Notification $notification): void;

    public function getById(Uuid $id): Notification;

    public function countNotificationInLastHourByReceiver(Uuid $receiverId): int;
}
