<?php

namespace package\Application\Model\ValueObject;

final class RowsPerPage
{
    public function __construct(int $value)
    {
        if ($value <= 0) {
            $this->value = self::defaultValue();
        } else {
            $this->value = $value;
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $value): bool
    {
        return $this->value() === $value->value();
    }

    public static function defaultValue(): int
    {
        return 10;
    }

    private $value;
}
