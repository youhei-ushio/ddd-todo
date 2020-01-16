<?php

namespace package\Domain\Service;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;
use package\Domain\Model\Entity\Task;
use package\Domain\Model\ValueObject\TaskTitle;

interface TaskRepository
{
    public function save(Task $task): void;

    /**
     * @param RowsPerPage $limit
     * @param PageNumber $pageNumber
     * @return Task[]
     */
    public function find(?RowsPerPage $limit, ?PageNumber $pageNumber): array;

    public function count(): int;

    public function exists(TaskTitle $title): bool;
}
