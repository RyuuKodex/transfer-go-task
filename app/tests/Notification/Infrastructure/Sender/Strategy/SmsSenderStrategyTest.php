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
        $texterMock = $this->createMock(TexterInterface::class);

        $smsSenderStrategy = new SmsSenderStrategy($texterMock);

        self::assertTrue($smsSenderStrategy->supports(new Channel('sms', [])));
    }

    public function testDoesntSupports(): void
    {
        $texterMock = $this->createMock(TexterInterface::class);

        $smsSenderStrategy = new SmsSenderStrategy($texterMock);

        self::assertFalse($smsSenderStrategy->supports(new Channel('email', [])));
    }

    public function testSend(): void
    {
        $channel = new Channel('sms', ['phoneNumber' => '+48 111222333']);

        $texterMock = $this->createMock(TexterInterface::class);
        $texterMock
            ->expects(self::once())
            ->method('send');

        $smsSenderStrategy = new SmsSenderStrategy($texterMock);

        $notificationMock = $this->createMock(Notification::class);
        $result = $smsSenderStrategy->send($channel, $notificationMock);

        self::assertInstanceOf(SuccessResult::class, $result);
    }

    public function testSendFailed(): void
    {
        $channel = new Channel('sms', ['phoneNumber' => '+48 111222333']);

        $exceptionMock = $this->createMock(TransportExceptionInterface::class);
        $texterMock = $this->createMock(TexterInterface::class);
        $texterMock
            ->expects(self::once())
            ->method('send')
            ->willThrowException($exceptionMock);

        $smsSenderStrategy = new SmsSenderStrategy($texterMock);

        $notificationMock = $this->createMock(Notification::class);
        $result = $smsSenderStrategy->send($channel, $notificationMock);

        self::assertInstanceOf(FailureResult::class, $result);
    }
}
