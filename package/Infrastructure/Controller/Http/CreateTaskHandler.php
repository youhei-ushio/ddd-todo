<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\CreateTaskRequest;
use package\Application\Service\CreateTaskService;

final class CreateTaskHandler
{
    public function handle(CreateTaskService $service): void
    {
        $title = '';
        if (array_key_exists('title', $_POST)) {
            $title = $_POST['title'];
        }
        $body = '';
        if (array_key_exists('body', $_POST)) {
            $body = $_POST['body'];
        }

        $service->handle(
            new CreateTaskRequest(
                $title,
                $body
            )
        );
    }
}
