<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\UserService\Dto;

final class Channel
{
    public function __construct(public string $name, public array $details)
    {
    }
}
