<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Domain\Service\CreateTaskDomainService;
use package\Presentation\CreateTaskPagePresenter;
use package\Presentation\CreateTaskPresenter;

final class CreateTaskService
{
    public function __construct(
        CreateTaskValidator $validator,
        CreateTaskDomainService $domainService,
        CreateTaskPresenter $createTaskPresenter,
        CreateTaskPagePresenter $createTaskPagePresenter)
    {
        $this->validator = $validator;
        $this->domainService = $domainService;
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

        $this->domainService->handle(
            $request->title(),
            $request->body()
        );

        $this->createTaskPresenter->output();
    }

    private $validator;
    private $domainService;
    private $createTaskPresenter;
    private $createTaskPagePresenter;
}
