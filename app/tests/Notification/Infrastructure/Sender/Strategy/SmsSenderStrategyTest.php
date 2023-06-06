<?php

declare(strict_types=1);

namespace App\Tests\Notification\Infrastructure\Sender\Strategy;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\Sender\Strategy\SmsSenderStrategy;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\TexterInterface;

final class SmsSenderStrategyTest extends TestCase
{
    public function testSupports(): void
    {
        $texter = $this->createMock(TexterInterface::class);

        $smsSenderStrategy = new SmsSenderStrategy($texter);

        self::assertTrue($smsSenderStrategy->supports(new Channel('sms', [])));
    }

    public function testDoesntSupports(): void
    {
        $texter = $this->createMock(TexterInterface::class);

        $smsSenderStrategy = new SmsSenderStrategy($texter);

        self::assertFalse($smsSenderStrategy->supports(new Channel('email', [])));
    }

    public function testSend(): void
    {
        $texter = $this->createMock(TexterInterface::class);
        $notification = $this->createMock(Notification::class);
        $channel = new Channel('sms', ['phoneNumber' => '+48 111222333']);

        $texter
            ->expects(self::once())
            ->method('send');

        $smsSenderStrategy = new SmsSenderStrategy($texter);

        $result = $smsSenderStrategy->send($channel, $notification);

        self::assertInstanceOf(SuccessResult::class, $result);
    }

    public function testSendFailed(): void
    {
        $texter = $this->createMock(TexterInterface::class);
        $notification = $this->createMock(Notification::class);
        $channel = new Channel('sms', ['phoneNumber' => '+48 111222333']);
        $exception = $this->createMock(TransportExceptionInterface::class);

        $texter
            ->expects(self::once())
            ->method('send')
            ->willThrowException($exception);

        $smsSenderStrategy = new SmsSenderStrategy($texter);

        $result = $smsSenderStrategy->send($channel, $notification);

        self::assertInstanceOf(FailureResult::class, $result);
    }
}
