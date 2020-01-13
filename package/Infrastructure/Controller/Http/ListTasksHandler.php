<?php

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\ListTasksRequest;
use package\Application\Service\ListTasksService;

final class ListTasksHandler
{
    public function handle(ListTasksService $service): void
    {
        $service->handle(
            new ListTasksRequest()
        );
    }
}
