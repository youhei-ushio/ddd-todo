<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\ListTasksRequest;
use package\Application\Service\ListTasksService;

final class ListTasksHandler
{
    public function handle(ListTasksService $service): void
    {
        $limit = null;
        if (array_key_exists('limit', $_GET)) {
            $limit = intval($_GET['limit']);
            if ($limit === 0) {
                $limit = null;
            }
        }
        $page = null;
        if (array_key_exists('page', $_GET)) {
            $page = intval($_GET['page']);
            if ($page === 0) {
                $page = null;
            }
        }
        $service->handle(
            new ListTasksRequest($limit, $page)
        );
    }
}
