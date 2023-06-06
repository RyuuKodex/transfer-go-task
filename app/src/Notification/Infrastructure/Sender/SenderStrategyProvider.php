<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Sender;

use App\Notification\Infrastructure\Sender\Strategy\SenderStrategyInterface;
use App\Notification\Infrastructure\UserService\Dto\Channel;

final readonly class SenderStrategyProvider implements SenderStrategyProviderInterface
{
    /** @param SenderStrategyInterface[] $strategies */
    public function __construct(private iterable $strategies)
    {
    }

    public function getStrategy(Channel $channel): SenderStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($channel)) {
                continue;
            }

            return $strategy;
        }

        throw new \RuntimeException('No strategy for given channel found.');
    }
}
