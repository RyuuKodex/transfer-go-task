<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\AbstractResult;
use App\Notification\Infrastructure\Sender\Strategy\SenderStrategyInterface;
use App\Notification\Infrastructure\UserService\Dto\Channel;

readonly class MockSenderStrategy implements SenderStrategyInterface
{
    public function __construct(private readonly bool $isSupporting, private readonly AbstractResult $result)
    {
    }

    public function supports(Channel $channel): bool
    {
        return $this->isSupporting;
    }

    public function send(Channel $channel, Notification $notification): AbstractResult
    {
        return $this->result;
    }
}
