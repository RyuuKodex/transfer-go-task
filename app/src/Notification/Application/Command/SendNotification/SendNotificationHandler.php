<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\SendNotification;

use App\Notification\Domain\Enum\NotificationStatus;
use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\Sender\SenderStrategyProviderInterface;
use App\Notification\Infrastructure\Specification\CanSendNextNotificationSpecificationInterface;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use App\Notification\Infrastructure\UserService\UserHttpClientInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNotificationHandler
{
    public function __construct(
        private NotificationStoreInterface $notificationStore,
        private UserHttpClientInterface $userHttpClient,
        private SenderStrategyProviderInterface $senderStrategyProvider,
        private CanSendNextNotificationSpecificationInterface $canSendNextNotificationSpecification
    ) {
    }

    public function __invoke(SendNotificationCommand $command): void
    {
        $notification = $this->notificationStore->getById($command->notificationId);
        $user = $this->userHttpClient->getUserById($notification->getReceiver());

        $this->sendMessage($notification, ...$user->channels);
    }

    public function sendMessage(Notification $notification, Channel ...$channels): void
    {
        if (!$this->canSendNextNotificationSpecification->canSend($notification)) {
            $notification->updateStatus(NotificationStatus::NotSentDueToThrottling);
            $this->notificationStore->store($notification);

            return;
        }

        foreach ($channels as $channel) {
            $strategy = $this->senderStrategyProvider->getStrategy($channel);
            $result = $strategy->send($channel, $notification);

            if (!$result instanceof SuccessResult) {
                continue;
            }

            $notification->updateStatus(NotificationStatus::Sent);
            $this->notificationStore->store($notification);

            return;
        }

        $notification->updateStatus(NotificationStatus::AllChannelsFailed);
        $this->notificationStore->store($notification);
    }
}
