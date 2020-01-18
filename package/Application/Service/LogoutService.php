<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Presentation\LogoutPresenter;

final class LogoutService
{
    public function __construct(
        Authenticator $authenticator,
        LogoutPresenter $logoutPresenter)
    {
        $this->authenticator = $authenticator;
        $this->logoutPresenter = $logoutPresenter;
    }

    public function handle(
        /** @noinspection PhpUnusedParameterInspection */
        LogoutRequest $request): void
    {
        $this->authenticator->logout();

        $this->logoutPresenter->output();
    }

    private $authenticator;
    private $logoutPresenter;
}
