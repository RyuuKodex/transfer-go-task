<?php

declare(strict_types=1);

namespace App\Tests\Notification\Application\Command;

use App\Notification\Application\Command\SendNotification\SendNotificationCommand;
use App\Notification\Application\Command\SendNotification\SendNotificationHandler;
use App\Notification\Domain\Enum\NotificationStatus;
use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\Sender\SenderStrategyProviderInterface;
use App\Notification\Infrastructure\Sender\Strategy\SenderStrategyInterface;
use App\Notification\Infrastructure\Specification\CanSendNextNotificationSpecificationInterface;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use App\Notification\Infrastructure\UserService\Dto\User;
use App\Notification\Infrastructure\UserService\UserHttpClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class SendNotificationHandlerTest extends TestCase
{
    public function testSendSuccessfully(): void
    {

        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationMock = $this->createMock(Notification::class);
        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notificationMock);

        $userHttpClientMock = $this->createMock(UserHttpClientInterface::class);
        $userHttpClientMock
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecificationMock = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $canSendNextNotificationSpecificationMock
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(true);

        $senderStrategyMock = $this->createMock(SenderStrategyInterface::class);
        $senderStrategyProviderMock = $this->createMock(SenderStrategyProviderInterface::class);
        $senderStrategyProviderMock
            ->expects(self::once())
            ->method('getStrategy')
            ->willReturn($senderStrategyMock);

        $senderStrategyMock
            ->expects(self::once())
            ->method('send')
            ->willReturn(new SuccessResult());

        $notificationMock->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::Sent);

        $notificationStoreMock
            ->expects(self::once())
            ->method('store')
            ->with($notificationMock);

        $command = new SendNotificationCommand($notificationId);

        $handler = new SendNotificationHandler(
            $notificationStoreMock,
            $userHttpClientMock,
            $senderStrategyProviderMock,
            $canSendNextNotificationSpecificationMock
        );

        $handler($command);
    }

    public function testNotSentDueToThrottling(): void
    {
        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationMock = $this->createMock(Notification::class);
        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notificationMock);

        $userHttpClientMock = $this->createMock(UserHttpClientInterface::class);
        $userHttpClientMock
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecificationMock = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $canSendNextNotificationSpecificationMock
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(false);

        $notificationMock
            ->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::NotSentDueToThrottling);

        $notificationStoreMock
            ->expects(self::once())
            ->method('store')
            ->with($notificationMock);

        $command = new SendNotificationCommand($notificationId);

        $senderStrategyProviderMock = $this->createMock(SenderStrategyProviderInterface::class);

        $handler = new SendNotificationHandler(
            $notificationStoreMock,
            $userHttpClientMock,
            $senderStrategyProviderMock,
            $canSendNextNotificationSpecificationMock
        );

        $handler($command);
    }

    public function testNotSentDueToAllChannelsFailed(): void
    {
        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationMock = $this->createMock(Notification::class);
        $notificationStoreMock = $this->createMock(NotificationStoreInterface::class);
        $notificationStoreMock
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notificationMock);

        $userHttpClientMock = $this->createMock(UserHttpClientInterface::class);
        $userHttpClientMock
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecificationMock = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $canSendNextNotificationSpecificationMock
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(true);

        $senderStrategyMock = $this->createMock(SenderStrategyInterface::class);
        $senderStrategyProviderMock = $this->createMock(SenderStrategyProviderInterface::class);
        $senderStrategyProviderMock
            ->expects(self::once())
            ->method('getStrategy')
            ->willReturn($senderStrategyMock);

        $senderStrategyMock
            ->expects(self::once())
            ->method('send')
            ->willReturn(new FailureResult());

        $notificationMock->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::AllChannelsFailed);

        $notificationStoreMock
            ->expects(self::once())
            ->method('store')
            ->with($notificationMock);

        $command = new SendNotificationCommand($notificationId);

        $handler = new SendNotificationHandler(
            $notificationStoreMock,
            $userHttpClientMock,
            $senderStrategyProviderMock,
            $canSendNextNotificationSpecificationMock
        );

        $handler($command);
    }
}
