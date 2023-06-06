<?php

declare(strict_types=1);

namespace App\Notification\Application\Listener;

use App\Notification\Application\Command\SendNotification\SendNotificationCommand;
use App\Notification\Application\Event\NotificationWasCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: NotificationWasCreatedEvent::class)]
final readonly class SendCreatedNotificationListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(NotificationWasCreatedEvent $event): void
    {
        $command = new SendNotificationCommand($event->notificationId);

        $this->messageBus->dispatch($command);
    }
}
