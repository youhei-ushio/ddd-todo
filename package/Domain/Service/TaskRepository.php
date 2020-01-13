<?php

namespace package\Domain\Service;

use package\Domain\Model\Entity\Task;
use package\Domain\Model\ValueObject\TaskTitle;

interface TaskRepository
{
    public function save(Task $task): void;

    /**
     * @return Task[]
     */
    public function find(): array;

    public function exists(TaskTitle $title): bool;
}
