<?php

declare(strict_types=1);

namespace Notification\Presentation\Response;

final class ShortSuccessResponse
{
    public function __construct(public int $code, public string $message, public array $details)
    {
    }
}
