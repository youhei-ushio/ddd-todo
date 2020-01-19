<?php
declare(strict_types=1);

namespace package\Domain\Service;

use package\Domain\Model\Entity\Task;
use package\Domain\Model\Event\TaskCreated;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;

final class CreateTaskDomainService
{
    public function __construct(
        TaskRepository $repository,
        EventPublisher $eventPublisher)
    {
        $this->repository = $repository;
        $this->eventPublisher = $eventPublisher;
    }

    public function handle(TaskTitle $title, TaskBody $body): void
    {
        $task = new Task(
            $title,
            $body
        );
        $this->repository->save($task);

        $this->eventPublisher->publish(new TaskCreated($task));
    }

    private $repository;
    private $eventPublisher;
}
