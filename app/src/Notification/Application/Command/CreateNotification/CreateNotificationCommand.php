<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\CreateNotification;

use Symfony\Component\Uid\Uuid;

final readonly class CreateNotificationCommand
{
    public function __construct(
        public Uuid $id,
        public Uuid $sender,
        public Uuid $receiver,
        public string $title,
        public string $message,
    ) {
    }
}
