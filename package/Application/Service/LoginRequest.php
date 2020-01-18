<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;

final class LoginRequest
{
    public function __construct(string $name, string $password)
    {
        $this->data = [
            'name' => $name,
            'password' => $password,
        ];
    }

    public function name(): AccountName
    {
        return new AccountName($this->data['name']);
    }

    public function password(): AccountPassword
    {
        return new AccountPassword($this->data['password']);
    }

    public function validate(LoginValidator $validator): array
    {
        return $validator->validate(
            $this->data['name'],
            $this->data['password']
        );
    }

    public function rawValues(): array
    {
        return $this->data;
    }

    private $data;
}
