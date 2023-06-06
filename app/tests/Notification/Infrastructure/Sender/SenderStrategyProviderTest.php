<?php

declare(strict_types=1);

namespace App\Tests\Notification\Infrastructure\Sender;

use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\Sender\SenderStrategyProvider;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use App\Tests\Mock\MockSenderStrategy;
use PHPUnit\Framework\TestCase;

final class SenderStrategyProviderTest extends TestCase
{
    public function testGetStrategy(): void
    {
        $strategy1 = new MockSenderStrategy(false, new SuccessResult());
        $strategy2 = new MockSenderStrategy(true, new SuccessResult());
        $strategy3 = new MockSenderStrategy(true, new SuccessResult());
        $channel = new Channel('sms', []);

        $strategyProvider = new SenderStrategyProvider([$strategy1, $strategy2, $strategy3]);
        $strategy = $strategyProvider->getStrategy($channel);
        self::assertSame($strategy, $strategy2);
    }

    public function testFailedToGetStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No strategy for given channel found.');

        $strategy1 = new MockSenderStrategy(false, new FailureResult());
        $strategy2 = new MockSenderStrategy(false, new FailureResult());
        $strategy3 = new MockSenderStrategy(false, new FailureResult());
        $channel = new Channel('sms', []);

        $strategyProvider = new SenderStrategyProvider([$strategy1, $strategy2, $strategy3]);
        $strategyProvider->getStrategy($channel);
    }
}
