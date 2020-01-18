<?php
declare(strict_types=1);

namespace package\Application\Model\ValueObject;

use InvalidArgumentException;

final class AccountName
{
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('空のアカウント名は許可されません。');
        }
        if (mb_strlen($value) > self::maxCharacters()) {
            throw new InvalidArgumentException(self::maxCharacters() . '文字以上のアカウント名は許可されません。');
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

    public static function maxCharacters(): int
    {
        return 20;
    }

    private $value;
}
