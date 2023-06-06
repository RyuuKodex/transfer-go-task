<?php

declare(strict_types=1);

namespace App\Tests\Notification\Application\Listener;

use App\Notification\Application\Event\NotificationWasCreatedEvent;
use App\Notification\Application\Listener\SendCreatedNotificationListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class SendCreatedNotificationListenerTest extends TestCase
{
    public function testListener(): void
    {
        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');

        $messageBusMock = $this->createMock(MessageBusInterface::class);
        $messageBusMock
            ->expects(self::once())
            ->method('dispatch')
            ->willReturn(new Envelope(new \stdClass()));

        $event = new NotificationWasCreatedEvent($notificationId);

        $listener = new SendCreatedNotificationListener($messageBusMock);
        $listener($event);
    }
}
