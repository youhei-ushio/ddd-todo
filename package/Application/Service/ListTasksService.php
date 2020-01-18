<?php
declare(strict_types=1);

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
        $maxPage = (int)ceil($total / $request->limit()->value());
        $firstIndex = $request->limit()->value() * ($request->page()->value() - 1);
        $lastIndex = $firstIndex + $request->limit()->value() - 1;
        if ($lastIndex >= $total) {
            $lastIndex = $total - 1;
        }
        $this->listTasksPresenter->output(
            $tasks,
            $request->limit(),
            $request->page(),
            new PageNumber($maxPage),
            $total,
            $firstIndex,
            $lastIndex
        );
    }

    private $repository;
    private $listTasksPresenter;
}
