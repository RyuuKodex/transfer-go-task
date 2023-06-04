<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\CreateNotification;

use App\Notification\Application\Event\NotificationWasCreatedEvent;
use App\Notification\Domain\Repository\NotificationRepositoryInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateNotificationHandler
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(CreateNotificationCommand $command): void
    {
        $notification = new Notification(
            $command->getSender(),
            $command->getReceiver(),
            $command->getTitle(),
            $command->getMessage()
        );

        $this->notificationRepository->store($notification);

        $event = new NotificationWasCreatedEvent($notification->getId());

        $this->eventDispatcher->dispatch($event);
    }
}
