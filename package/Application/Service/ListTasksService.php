<?php

namespace package\Application\Service;

use package\Domain\Service\TaskRepository;
use package\Presentation\ListTasksPresenter;

final class ListTasksService
{
    public function __construct(
        TaskRepository $repository,
        ListTasksPresenter $listTasksPresenter)
    {
        $this->repository = $repository;
        $this->listTasksPresenter = $listTasksPresenter;
    }

    public function handle(ListTasksRequest $request): void
    {
        $tasks = $this->repository->find();
        $this->listTasksPresenter->output($tasks);
    }

    private $repository;
    private $listTasksPresenter;
}
