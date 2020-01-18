<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\LogoutRequest;
use package\Application\Service\LogoutService;

final class LogoutHandler
{
    public function handle(LogoutService $service): void
    {
        $service->handle(
            new LogoutRequest()
        );
    }
}
