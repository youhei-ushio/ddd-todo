<?php

namespace package\Application\Service;

use package\Presentation\CreateTaskPagePresenter;

final class CreateTaskPageService
{
    public function __construct(CreateTaskPagePresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function handle(CreateTaskPageRequest $request): void
    {
        $this->presenter->output([], []);
    }

    private $presenter;
}
