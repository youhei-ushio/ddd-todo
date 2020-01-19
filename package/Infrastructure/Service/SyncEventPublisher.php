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
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber['event'] === $className) {
                $subscriber['subscriber']->handle($event);
            }
        }
    }

    public function addSubscriber(string $eventClassName, $subscriber): void
    {
        $this->subscribers[] = [
            'event' => $eventClassName,
            'subscriber' => $subscriber,
        ];
    }

    private $subscribers = [];
}
