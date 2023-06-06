<?php

declare(strict_types=1);

namespace App\Tests\Notification\Application\Command;

use App\Notification\Application\Command\CreateNotification\CreateNotificationCommand;
use App\Notification\Application\Command\CreateNotification\CreateNotificationHandler;
use App\Notification\Application\Event\NotificationWasCreatedEvent;
use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Uuid;

final class CreateNotificationHandlerTest extends TestCase
{
    public function testCreate(): void
    {
        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $sender = Uuid::fromString('f13c1b0f-c5d5-4ea4-ad62-4a347b1e345a');
        $receiver = Uuid::fromString('9954e81c-b3a0-4271-82f5-59519aa51a06');
        $title = 'title';
        $message = 'message';

        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock->expects(self::once())
            ->method('store')
            ->with(
                self::callback(fn (Notification $notification): bool => $notification->getId() === $notificationId
                    && $notification->getSender() === $sender
                    && $notification->getReceiver() === $receiver
                    && $notification->getTitle() === $title
                    && $notification->getMessage() === $message
                )
            );

        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcherMock->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(
                    fn (NotificationWasCreatedEvent $event): bool => $event->notificationId === $notificationId
                )
            );

        $command = new CreateNotificationCommand($notificationId, $sender, $receiver, $title, $message);
        $handler = new CreateNotificationHandler($notificationStoreMock, $eventDispatcherMock);

        $handler($command);
    }
}
