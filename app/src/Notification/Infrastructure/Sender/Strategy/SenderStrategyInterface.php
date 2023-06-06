<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Sender\Strategy;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\AbstractResult;
use App\Notification\Infrastructure\UserService\Dto\Channel;

interface SenderStrategyInterface
{
    public function supports(Channel $channel): bool;

    public function send(Channel $channel, Notification $notification): AbstractResult;
}
