<?php
declare(strict_types=1);

namespace package\Presentation;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;
use package\Domain\Model\Entity\Task;

interface ListTasksPresenter
{
    /**
     * @param Task[] $tasks
     * @param RowsPerPage $limit
     * @param PageNumber $pageNumber
     * @param PageNumber $maxPage
     * @param int $total
     * @param int $firstIndex
     * @param int $lastIndex
     */
    public function output(
        array $tasks,
        RowsPerPage $limit,
        PageNumber $pageNumber,
        PageNumber $maxPage,
        int $total,
        int $firstIndex,
        int $lastIndex
    ): void;
}
