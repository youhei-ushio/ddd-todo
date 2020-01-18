<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http;

use package\Application\Service\LoginRequest;
use package\Application\Service\LoginService;

final class LoginHandler
{
    public function handle(LoginService $service): void
    {
        $name = '';
        if (array_key_exists('name', $_POST)) {
            $name = $_POST['name'];
        }
        $password = '';
        if (array_key_exists('password', $_POST)) {
            $password = $_POST['password'];
        }

        $service->handle(
            new LoginRequest(
                $name,
                $password
            )
        );
    }
}
