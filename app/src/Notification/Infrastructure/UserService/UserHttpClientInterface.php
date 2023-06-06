<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\UserService;

use App\Notification\Infrastructure\UserService\Dto\User;
use Symfony\Component\Uid\Uuid;

interface UserHttpClientInterface
{
    public function getUserById(Uuid $id): User;
}
