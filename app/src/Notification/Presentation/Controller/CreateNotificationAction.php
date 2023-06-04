<?php

declare(strict_types=1);

namespace App\Notification\Presentation\Controller;

use App\Notification\Application\Command\CreateNotification\CreateNotificationCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class CreateNotificationAction extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/notification', methods: 'POST')]
    public function create(): JsonResponse
    {
        $this->messageBus->dispatch(
            new CreateNotificationCommand(
                Uuid::v4(),
                Uuid::v4(),
                'title',
                'message'
            )
        );

        return new JsonResponse('ok');
    }
}
