<?php
declare(strict_types=1);

namespace package\Infrastructure\Service;

use package\Domain\Model\ValueObject\Event;
use package\Domain\Service\EventPublisher;

class SyncEventPublisher implements EventPublisher
{
    public function publish(Event $event): void
    {
        $className = get_class($event);
        if (array_key_exists($className, $this->subscribers)) {
            $this->subscribers[$className]->handle($event);
        }
    }

    public function addSubscriber(string $eventClassName, $subscriber): void
    {
        $this->subscribers[$eventClassName] = $subscriber;
    }

    private $subscribers = [];
}
