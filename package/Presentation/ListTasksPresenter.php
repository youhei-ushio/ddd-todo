<?php

namespace package\Presentation;

use package\Domain\Model\Entity\Task;

interface ListTasksPresenter
{
    /**
     * @param Task[] $tasks
     */
    public function render(array $tasks): void;
}
