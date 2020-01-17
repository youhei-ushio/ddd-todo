<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Domain\Model\Entity\Task;
use package\Domain\Service\TaskRepository;
use package\Presentation\CreateTaskPagePresenter;
use package\Presentation\CreateTaskPresenter;

final class CreateTaskService
{
    public function __construct(
        CreateTaskValidator $validator,
        TaskRepository $repository,
        CreateTaskPresenter $createTaskPresenter,
        CreateTaskPagePresenter $createTaskPagePresenter)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->createTaskPresenter = $createTaskPresenter;
        $this->createTaskPagePresenter = $createTaskPagePresenter;
    }

    public function handle(CreateTaskRequest $request): void
    {
        $errors = $request->validate($this->validator);
        if (count($errors) > 0) {
            $this->createTaskPagePresenter->output($errors, $request->rawValues());
            return;
        }

        $task = new Task(
            $request->title(),
            $request->body()
        );
        $this->repository->save($task);

        $this->createTaskPresenter->output();
    }

    private $validator;
    private $repository;
    private $createTaskPresenter;
    private $createTaskPagePresenter;
}
