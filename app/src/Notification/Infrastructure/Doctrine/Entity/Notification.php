<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Entity;

use App\Notification\Domain\Enum\NotificationStatus;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Notification\Infrastructure\Doctrine\Repository\NotificationRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]

class Notification
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'uuid')]
    private Uuid $sender;

    #[ORM\Column(type: 'uuid')]
    private Uuid $receiver;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    private string $status;

    public function __construct(Uuid $sender, Uuid $receiver, string $title, string $message)
    {
        $this->id = Uuid::v4();
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->title = $title;
        $this->message = $message;

        $this->createdAt = new DateTimeImmutable();
        $this->updateStatus(NotificationStatus::Created);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSender(): Uuid
    {
        return $this->sender;
    }

    public function setSender(Uuid $sender): void
    {
        $this->sender = $sender;
    }

    public function getReceiver(): Uuid
    {
        return $this->receiver;
    }

    public function setReceiver(Uuid $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getStatus(): NotificationStatus
    {
        return NotificationStatus::from($this->status);
    }

    public function updateStatus(NotificationStatus $status): void
    {
        $this->status = $status->value;
    }
}
