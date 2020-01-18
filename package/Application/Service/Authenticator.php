<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;

interface Authenticator
{
    public function login(AccountName $name, AccountPassword $password): bool;

    public function isLoggedIn(): bool;

    public function logout(): void;
}
