<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Presentation\LoginPagePresenter;

final class LoginPageService
{
    public function __construct(LoginPagePresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function handle(
        /** @noinspection PhpUnusedParameterInspection */
        LoginPageRequest $request): void
    {
        $this->presenter->output([], []);
    }

    private $presenter;
}
