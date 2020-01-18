<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;
use package\Application\Service\Authenticator;

class FailureAuthenticator implements Authenticator
{
    public function login(AccountName $name, AccountPassword $password): bool
    {
        return false;
    }

    public function isLoggedIn(): bool
    {
        return false;
    }

    public function logout(): void
    {

    }
}
