<?php

declare(strict_types=1);

namespace App\Tests\Notification\Infrastructure\Sender\Strategy;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\Sender\Strategy\EmailSenderStrategy;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

final class EmailSenderStrategyTest extends TestCase
{
    public function testSupports(): void
    {
        $mailerMock = $this->createMock(MailerInterface::class);

        $emailSenderStrategy = new EmailSenderStrategy($mailerMock);

        self::assertTrue($emailSenderStrategy->supports(new Channel('email', [])));
    }

    public function testDoesntSupports(): void
    {
        $mailerMock = $this->createMock(MailerInterface::class);

        $emailSenderStrategy = new EmailSenderStrategy($mailerMock);

        self::assertFalse($emailSenderStrategy->supports(new Channel('sms', [])));
    }

    public function testSend(): void
    {
        $mailerMock = $this->createMock(MailerInterface::class);
        $notificationMock = $this->createMock(Notification::class);
        $channel = new Channel('email', ['email' => 'test@email.com']);

        $mailerMock
            ->expects(self::once())
            ->method('send');

        $emailSenderStrategy = new EmailSenderStrategy($mailerMock);

        $result = $emailSenderStrategy->send($channel, $notificationMock);

        self::assertInstanceOf(SuccessResult::class, $result);
    }

    public function testSendFailed(): void
    {
        $channel = new Channel('email', ['email' => 'test2@email.com']);

        $exceptionMock = $this->createMock(TransportExceptionInterface::class);
        $mailerMock = $this->createMock(MailerInterface::class);
        $mailerMock
            ->expects(self::once())
            ->method('send')
            ->willThrowException($exceptionMock);

        $emailSenderStrategy = new EmailSenderStrategy($mailerMock);

        $notificationMock = $this->createMock(Notification::class);
        $result = $emailSenderStrategy->send($channel, $notificationMock);

        self::assertInstanceOf(FailureResult::class, $result);
    }
}
