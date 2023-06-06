<?php

declare(strict_types=1);

namespace Notification\Presentation\Controller;

use App\Notification\Infrastructure\Doctrine\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateNotificationActionTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();
        $kernel = static::bootKernel();

        $data = [
            'sender' => '61407079-0246-47ae-8077-29039e5d798e',
            'receiver' => '494572c8-d727-4753-b39f-da2fe935cd68',
            'title' => 'title',
            'message' => 'message',
        ];

        $client->request('POST', '/api/notification', content: json_encode($data));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /** @var NotificationRepository $repository */
        $repository = $kernel->getContainer()->get(NotificationRepository::class);
        $notification = $repository->findOneBy(['sender' => '61407079-0246-47ae-8077-29039e5d798e']);

        self::assertEquals('61407079-0246-47ae-8077-29039e5d798e', $notification->getSender());
        self::assertEquals('494572c8-d727-4753-b39f-da2fe935cd68', $notification->getReceiver());
        self::assertEquals('title', $notification->getTitle());
        self::assertEquals('message', $notification->getMessage());
    }
}
