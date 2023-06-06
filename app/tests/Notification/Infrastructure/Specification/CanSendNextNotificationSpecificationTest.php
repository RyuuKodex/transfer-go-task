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
        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock
            ->expects(self::once())
            ->method('countNotificationInLastHourByReceiver')
            ->willReturn(26);

        $specification = new CanSendNextNotificationSpecification($notificationStoreMock, 300);

        $notificationMock = $this->createMock(Notification::class);
        self::assertTrue($specification->canSend($notificationMock));
    }

    public function testCannotSend(): void
    {
        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock
            ->expects(self::once())
            ->method('countNotificationInLastHourByReceiver')
            ->willReturn(332);

        $specification = new CanSendNextNotificationSpecification($notificationStoreMock, 300);

        $notificationMock = $this->createMock(Notification::class);
        self::assertFalse($specification->canSend($notificationMock));
    }
}
