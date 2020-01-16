<?php

namespace package\Application\Service;

use package\Application\Model\ValueObject\PageNumber;
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
        $tasks = $this->repository->find($request->limit(), $request->page());
        $total = $this->repository->count();
        $maxPage = ceil($total / $request->limit()->value());
        $this->listTasksPresenter->output(
            $tasks,
            $request->limit(),
            $request->page(),
            new PageNumber($maxPage)
        );
    }

    private $repository;
    private $listTasksPresenter;
}
