<?php

namespace package\Presentation;

use package\Domain\Model\Entity\Task;

interface ViewTaskPresenter
{
    public function output(Task $task): void;
}
