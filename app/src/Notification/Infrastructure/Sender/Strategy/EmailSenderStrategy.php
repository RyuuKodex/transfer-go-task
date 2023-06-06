<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Sender\Strategy;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Sender\Result\AbstractResult;
use App\Notification\Infrastructure\Sender\Result\FailureResult;
use App\Notification\Infrastructure\Sender\Result\SuccessResult;
use App\Notification\Infrastructure\UserService\Dto\Channel;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailSenderStrategy implements SenderStrategyInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function supports(Channel $channel): bool
    {
        return 'email' === $channel->name;
    }

    public function send(Channel $channel, Notification $notification): AbstractResult
    {
        $email = new Email();

        $email
            ->from('test@email.com')
            ->to($channel->details['email'])
            ->subject($notification->getTitle())
            ->text($notification->getMessage());

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
            return new FailureResult();
        }

        return new SuccessResult();
    }
}
