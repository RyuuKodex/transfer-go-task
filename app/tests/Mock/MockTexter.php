<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\TexterInterface;

readonly class MockTexter implements TexterInterface
{
    public function send(MessageInterface $message): ?SentMessage
    {
        return new SentMessage($message, 'mock_transport');
    }

    public function supports(MessageInterface $message): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return '';
    }
}
