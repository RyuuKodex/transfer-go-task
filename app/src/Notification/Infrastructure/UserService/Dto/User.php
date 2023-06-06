<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\UserService\Dto;

use Symfony\Component\Uid\Uuid;

final readonly class User
{
    /** @param Channel[] $channels */
    public function __construct(
        public Uuid $id,
        public array $channels
    ) {
    }
}
