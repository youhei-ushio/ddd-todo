<?php

namespace package\Domain\Model\ValueObject;

use InvalidArgumentException;

final class TaskTitle
{
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('空のタイトルは許可されません。');
        }
        if (strpos($value, '/') !== false) {
            throw new InvalidArgumentException('タイトルに / (スラッシュ)は使用できません。');
        }
        if (strpos($value, '.') !== false) {
            throw new InvalidArgumentException('タイトルに . (ドット)は使用できません。');
        }
        if (mb_strlen($value) > self::maxCharacters()) {
            throw new InvalidArgumentException(self::maxCharacters() . '文字以上のタイトルは許可されません。');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function maxCharacters(): int
    {
        return 20;
    }

    private $value;
}
