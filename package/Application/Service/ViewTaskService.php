<?php

namespace package\Application\Service;

use package\Domain\Service\TaskRepository;
use package\Presentation\NotFoundPresenter;
use package\Presentation\ViewTaskPresenter;

final class ViewTaskService
{
    public function __construct(
        ViewTaskValidator $validator,
        TaskRepository $repository,
        ViewTaskPresenter $viewTaskPresenter,
        NotFoundPresenter $notFoundPresenter)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->viewTaskPresenter = $viewTaskPresenter;
        $this->notFoundPresenter = $notFoundPresenter;
    }

    public function handle(ViewTaskRequest $request): void
    {
        $errors = $request->validate($this->validator);
        if (count($errors) > 0) {
            $this->notFoundPresenter->output();
            return;
        }

        $task = $this->repository->findByTitle($request->title());
        if ($task === null) {
            $this->notFoundPresenter->output();
            return;
        }

        $this->viewTaskPresenter->output($task);
    }

    private $validator;
    private $repository;
    private $viewTaskPresenter;
    private $notFoundPresenter;
}
