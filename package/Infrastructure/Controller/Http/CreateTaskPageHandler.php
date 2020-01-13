<?php

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\CreateTaskPageRequest;
use package\Application\Service\CreateTaskPageService;

final class CreateTaskPageHandler
{
    public function handle(CreateTaskPageService $service): void
    {
        $service->handle(new CreateTaskPageRequest());
    }
}
