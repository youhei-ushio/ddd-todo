<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\LoginPageRequest;
use package\Application\Service\LoginPageService;

final class LoginPageHandler
{
    public function handle(LoginPageService $service): void
    {
        $service->handle(new LoginPageRequest());
    }
}
