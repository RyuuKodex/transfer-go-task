<?php

declare(strict_types=1);

namespace App\Tests\Notification\Infrastructure\Specification;

use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Specification\CanSendNextNotificationSpecification;
use PHPUnit\Framework\TestCase;

final class CanSendNextNotificationSpecificationTest extends TestCase
{
    public function testCanSend(): void
    {
        $notificationStore = $this->createMock(NotificationStoreInterface::class);
        $notification = $this->createMock(Notification::class);

        $notificationStore
            ->expects(self::once())
            ->method('countNotificationInLastHourByReceiver')
            ->willReturn(26);

        $specification = new CanSendNextNotificationSpecification($notificationStore, 300);

        self::assertTrue($specification->canSend($notification));
    }

    public function testCannotSend(): void
    {
        $notificationStore = $this->createMock(NotificationStoreInterface::class);
        $notification = $this->createMock(Notification::class);

        $notificationStore
            ->expects(self::once())
            ->method('countNotificationInLastHourByReceiver')
            ->willReturn(332);

        $specification = new CanSendNextNotificationSpecification($notificationStore, 300);

        self::assertFalse($specification->canSend($notification));
    }
}
