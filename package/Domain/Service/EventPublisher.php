<?php
declare(strict_types=1);

namespace package\Domain\Service;

use package\Domain\Model\ValueObject\Event;

interface EventPublisher
{
    public function publish(Event $event): void;
}
