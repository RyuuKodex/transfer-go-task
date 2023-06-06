<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Sender\Strategy;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\AbstractResult;
use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class SmsSenderStrategy implements SenderStrategyInterface
{
    public function __construct(private TexterInterface $texter)
    {
    }

    public function supports(Channel $channel): bool
    {
        return 'sms' === $channel->name;
    }

    public function send(Channel $channel, Notification $notification): AbstractResult
    {
        $sms = new SmsMessage($channel->details['phoneNumber'], $notification->getMessage());

        try {
            $this->texter->send($sms);
        } catch (TransportExceptionInterface $exception) {
            return new FailureResult();
        }

        return new SuccessResult();
    }
}
