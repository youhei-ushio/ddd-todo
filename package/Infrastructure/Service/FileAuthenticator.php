<?php
declare(strict_types=1);

namespace package\Infrastructure\Service;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;
use package\Application\Service\Authenticator;

class FileAuthenticator implements Authenticator
{
    public function login(AccountName $name, AccountPassword $password): bool
    {
        $directory = __DIR__;
        $directory = dirname($directory);
        $directory = dirname($directory);
        $directory = dirname($directory);
        $account = file_get_contents("{$directory}/.account");
        if ($account === false || $account === '') {
            return false;
        }
        $lines = explode("\n", $account);
        if (!$name->equals(new AccountName($lines[0])) || !$password->equals(new AccountPassword($lines[1]))) {
            return false;
        }

        session_start();
        session_regenerate_id();
        $_SESSION['account'] = $name->value();
        return true;
    }

    public function isLoggedIn(): bool
    {
        session_start();
        return array_key_exists('account', $_SESSION) && $_SESSION['account'] !== '';
    }

    public function logout(): void
    {
        session_destroy();
    }
}
