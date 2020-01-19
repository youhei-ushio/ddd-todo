<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;
use package\Application\Service\Authenticator;

final class SuccessAuthenticator implements Authenticator
{
    public function login(AccountName $name, AccountPassword $password): bool
    {
        return true;
    }

    public function isLoggedIn(): bool
    {
        return true;
    }

    public function logout(): void
    {

    }
}
