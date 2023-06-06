<?php

declare(strict_types=1);

namespace App\Notification\Presentation\Controller;

use App\Notification\Application\Command\CreateNotification\CreateNotificationCommand;
use Notification\Presentation\Response\ShortSuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class CreateNotificationAction extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/notification', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $data = json_decode($content, true);

        $notificationId = Uuid::v4();

        $command = new CreateNotificationCommand(
            $notificationId,
            Uuid::fromString($data['sender']),
            Uuid::fromString($data['receiver']),
            $data['title'],
            $data['message']
        );

        $this->messageBus->dispatch($command);

        $response = new ShortSuccessResponse(
            Response::HTTP_OK,
            'Notification created successfully.',
            ['notificationId' => $notificationId]
        );

        return new JsonResponse($response);
    }
}
