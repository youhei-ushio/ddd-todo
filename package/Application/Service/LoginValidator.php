<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Application\Model\ValueObject\ValidationError;

final class LoginValidator
{
    /**
     * @param string $name
     * @param string $password
     * @return ValidationError[]
     */
    public function validate(string $name, string $password): array
    {
        // ログイン画面なので空かどうかのバリデーションしか行わない

        $errors = [];
        if ($name === '') {
            $errors[] = new ValidationError('name', 'アカウント名を入力してください。');
        }

        if ($password === '') {
            $errors[] = new ValidationError('password', 'パスワードを入力してください。');
        }
        return $errors;
    }
}
