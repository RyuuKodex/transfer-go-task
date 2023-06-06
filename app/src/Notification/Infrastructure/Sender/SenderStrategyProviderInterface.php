<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Sender;

use App\Notification\Infrastructure\Sender\Strategy\SenderStrategyInterface;
use App\Notification\Infrastructure\UserService\Dto\Channel;

interface SenderStrategyProviderInterface
{
    public function getStrategy(Channel $channel): SenderStrategyInterface;
}
