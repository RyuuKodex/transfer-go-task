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
        $notificationStore = $this->createMock(NotificationStoreInterface::class);
        $senderStrategyProvider = $this->createMock(SenderStrategyProviderInterface::class);
        $senderStrategy = $this->createMock(SenderStrategyInterface::class);
        $userHttpClient = $this->createMock(UserHttpClientInterface::class);
        $canSendNextNotificationSpecification = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $notification = $this->createMock(Notification::class);

        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationStore
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notification);

        $userHttpClient
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecification
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(true);

        $senderStrategyProvider
            ->expects(self::once())
            ->method('getStrategy')
            ->willReturn($senderStrategy);

        $senderStrategy
            ->expects(self::once())
            ->method('send')
            ->willReturn(new SuccessResult());

        $notification->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::Sent);

        $notificationStore
            ->expects(self::once())
            ->method('store')
            ->with($notification);

        $command = new SendNotificationCommand($notificationId);

        $handler = new SendNotificationHandler(
            $notificationStore,
            $userHttpClient,
            $senderStrategyProvider,
            $canSendNextNotificationSpecification
        );

        $handler($command);
    }

    public function testNotSentDueToThrottling(): void
    {
        $notificationStore = $this->createMock(NotificationStoreInterface::class);
        $senderStrategyProvider = $this->createMock(SenderStrategyProviderInterface::class);
        $senderStrategy = $this->createMock(SenderStrategyInterface::class);
        $userHttpClient = $this->createMock(UserHttpClientInterface::class);
        $canSendNextNotificationSpecification = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $notification = $this->createMock(Notification::class);

        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationStore
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notification);

        $userHttpClient
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecification
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(false);

        $notification
            ->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::NotSentDueToThrottling);

        $notificationStore
            ->expects(self::once())
            ->method('store')
            ->with($notification);

        $command = new SendNotificationCommand($notificationId);

        $handler = new SendNotificationHandler(
            $notificationStore,
            $userHttpClient,
            $senderStrategyProvider,
            $canSendNextNotificationSpecification
        );

        $handler($command);
    }

    public function testNotSent(): void
    {
        $notificationStore = $this->createMock(NotificationStoreInterface::class);
        $senderStrategyProvider = $this->createMock(SenderStrategyProviderInterface::class);
        $senderStrategy = $this->createMock(SenderStrategyInterface::class);
        $userHttpClient = $this->createMock(UserHttpClientInterface::class);
        $canSendNextNotificationSpecification = $this->createMock(CanSendNextNotificationSpecificationInterface::class);
        $notification = $this->createMock(Notification::class);

        $notificationId = Uuid::fromString('4af2b8e5-53d0-4c31-b93f-61c346d3577b');
        $channel = new Channel('sms', ['phoneNumber' => '+48 111 222 333']);
        $user = new User(Uuid::fromString('9c1c5311-4a52-47f2-8267-f9b2ae21b087'), [$channel]);

        $notificationStore
            ->expects(self::once())
            ->method('getById')
            ->with($notificationId)
            ->willReturn($notification);

        $userHttpClient
            ->expects(self::once())
            ->method('getUserById')
            ->willReturn($user);

        $canSendNextNotificationSpecification
            ->expects(self::once())
            ->method('canSend')
            ->willReturn(true);

        $senderStrategyProvider
            ->expects(self::once())
            ->method('getStrategy')
            ->willReturn($senderStrategy);

        $senderStrategy
            ->expects(self::once())
            ->method('send')
            ->willReturn(new FailureResult());

        $notification->expects(self::once())
            ->method('updateStatus')
            ->with(NotificationStatus::AllChannelsFailed);

        $notificationStore
            ->expects(self::once())
            ->method('store')
            ->with($notification);

        $command = new SendNotificationCommand($notificationId);

        $handler = new SendNotificationHandler(
            $notificationStore,
            $userHttpClient,
            $senderStrategyProvider,
            $canSendNextNotificationSpecification
        );

        $handler($command);
    }
}
