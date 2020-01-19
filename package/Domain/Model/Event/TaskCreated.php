<?php
declare(strict_types=1);

namespace package\Domain\Model\Event;

use package\Domain\Model\Entity\Task;
use package\Domain\Model\ValueObject\Event;

final class TaskCreated extends Event
{
    public function __construct(Task $task)
    {
        parent::__construct();
        $this->task = $task;
    }

    public function task(): Task
    {
        return $this->task;
    }

    private $task;
}
