<?php

declare(strict_types=1);

namespace App\Notification\Domain\Repository;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;

interface NotificationRepositoryInterface
{
    public function store(Notification $notification): void;
}
