<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Domain\Model\Entity\Task;
use package\Domain\Model\Event\TaskCreated;

class CreateTaskSubscriber
{
    public function handle(TaskCreated $event)
    {
        $this->lastPublished = $event->task();
    }

    public function lastPublished(): ?Task
    {
        return $this->lastPublished;
    }

    private $lastPublished = null;
}
