<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\CreateNotification;

use Symfony\Component\Uid\Uuid;

final readonly class CreateNotificationCommand
{
    public function __construct(
        private Uuid $sender,
        private Uuid $receiver,
        private string $title,
        private string $message,
    ) {
    }

    public function getSender(): Uuid
    {
        return $this->sender;
    }

    public function getReceiver(): Uuid
    {
        return $this->receiver;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
