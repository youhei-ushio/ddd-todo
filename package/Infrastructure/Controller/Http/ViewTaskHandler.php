<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\ViewTaskRequest;
use package\Application\Service\ViewTaskService;

final class ViewTaskHandler
{
    public function handle(ViewTaskService $service): void
    {
        $title = '';
        if (array_key_exists('task', $_GET)) {
            $title = $_GET['task'];
        }

        $service->handle(
            new ViewTaskRequest(
                $title
            )
        );
    }
}
