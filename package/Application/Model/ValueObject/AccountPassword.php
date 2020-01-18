<?php
declare(strict_types=1);

namespace package\Application\Model\ValueObject;

use InvalidArgumentException;

final class AccountPassword
{
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('空のアカウントパスワードは許可されません。');
        }
        if (mb_strlen($value) < self::minCharacters()) {
            throw new InvalidArgumentException(self::minCharacters() . '文字未満のアカウントパスワードは許可されません。');
        }
        if (mb_strlen($value) > self::maxCharacters()) {
            throw new InvalidArgumentException((self::maxCharacters() + 1) . '文字以上のアカウントパスワードは許可されません。');
        }
        if (preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/', $value) === false) {
            throw new InvalidArgumentException('アカウントパスワードには半角英数字の大文字と小文字を両方が必要です。');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $value): bool
    {
        return $this->value() === $value->value();
    }

    public static function minCharacters(): int
    {
        return 8;
    }

    public static function maxCharacters(): int
    {
        return 20;
    }

    private $value;
}
