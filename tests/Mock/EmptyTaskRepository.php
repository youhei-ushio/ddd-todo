<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;
use package\Domain\Model\Entity\Task;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Domain\Service\TaskRepository;

class EmptyTaskRepository implements TaskRepository
{
    public function save(Task $task): void
    {

    }

    /**
     * @inheritDoc
     */
    public function find(?RowsPerPage $limit, ?PageNumber $pageNumber): array
    {
        return [];
    }

    public function count(): int
    {
        return 0;
    }

    public function exists(TaskTitle $title): bool
    {
        return false;
    }

    public function findByTitle(TaskTitle $title): ?Task
    {
        return null;
    }
}
