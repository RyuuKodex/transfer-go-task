<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\CreateNotification;

use App\Notification\Application\Event\NotificationWasCreatedEvent;
use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateNotificationHandler
{
    public function __construct(
        private NotificationStoreInterface $notificationStore,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(CreateNotificationCommand $command): void
    {
        $notification = new Notification(
            $command->id,
            $command->sender,
            $command->receiver,
            $command->title,
            $command->message
        );
        $this->notificationStore->store($notification);

        $event = new NotificationWasCreatedEvent($notification->getId());

        $this->eventDispatcher->dispatch($event);
    }
}
