<?php

namespace package\Domain\Model\ValueObject;

use InvalidArgumentException;

final class TaskBody
{
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('空の本文は許可されません。');
        }
        if (mb_strlen($value) > self::maxCharacters()) {
            throw new InvalidArgumentException(self::maxCharacters() . '文字以上の本文は許可されません。');
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
        return 500;
    }

    private $value;
}
