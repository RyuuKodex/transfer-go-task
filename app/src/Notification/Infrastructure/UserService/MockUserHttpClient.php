<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\UserService;

use App\Notification\Infrastructure\UserService\Dto\Channel;
use App\Notification\Infrastructure\UserService\Dto\User;
use Symfony\Component\Uid\Uuid;

final class MockUserHttpClient implements UserHttpClientInterface
{
    public function getUserById(Uuid $id): User
    {
        $channels = [
            new Channel('sms', ['phoneNumber' => '+48111222333']),
            new Channel('email', ['email' => 'test@test.pl']),
        ];

        return new User($id, $channels);
    }
}
