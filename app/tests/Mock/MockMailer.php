<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class MockMailer implements MailerInterface
{
    public function send(RawMessage $message, Envelope $envelope = null): void
    {
    }
}
