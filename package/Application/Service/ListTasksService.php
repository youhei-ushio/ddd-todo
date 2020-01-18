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
        $first = $request->limit()->value() * ($request->page()->value() - 1) + 1;
        if ($first >= $total) {
            $first = $total;
        }
        $last = $first + $request->limit()->value() - 1;
        if ($last >= $total) {
            $last = $total;
        }
        $this->listTasksPresenter->output(
            $tasks,
            $request->limit(),
            $request->page(),
            new PageNumber($maxPage),
            $total,
            $first,
            $last
        );
    }

    private $repository;
    private $listTasksPresenter;
}
