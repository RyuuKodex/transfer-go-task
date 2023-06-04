<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\SendNotification;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendNotificationCommandHandler
{
    public function __invoke(SendNotificationCommand $command)
    {
        // TODO: Implement __invoke() method.
    }
}
