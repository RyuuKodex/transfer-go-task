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
        $mailer = $this->createMock(MailerInterface::class);

        $emailSenderStrategy = new EmailSenderStrategy($mailer);

        self::assertTrue($emailSenderStrategy->supports(new Channel('email', [])));
    }

    public function testDoesntSupports(): void
    {
        $mailer = $this->createMock(MailerInterface::class);

        $emailSenderStrategy = new EmailSenderStrategy($mailer);

        self::assertFalse($emailSenderStrategy->supports(new Channel('sms', [])));
    }

    public function testSend(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $notification = $this->createMock(Notification::class);
        $channel = new Channel('email', ['email' => 'test@email.com']);

        $mailer
            ->expects(self::once())
            ->method('send');

        $emailSenderStrategy = new EmailSenderStrategy($mailer);

        $result = $emailSenderStrategy->send($channel, $notification);

        self::assertInstanceOf(SuccessResult::class, $result);
    }

    public function testSendFailed(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $notification = $this->createMock(Notification::class);
        $channel = new Channel('email', ['email' => 'test2@email.com']);
        $exception = $this->createMock(TransportExceptionInterface::class);

        $mailer
            ->expects(self::once())
            ->method('send')
            ->willThrowException($exception);

        $emailSenderStrategy = new EmailSenderStrategy($mailer);

        $result = $emailSenderStrategy->send($channel, $notification);

        self::assertInstanceOf(FailureResult::class, $result);
    }
}
