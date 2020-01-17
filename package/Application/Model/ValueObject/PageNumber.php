<?php
declare(strict_types=1);

namespace package\Application\Model\ValueObject;

final class PageNumber
{
    public function __construct(int $value)
    {
        if ($value <= 0) {
            $this->value = 1;
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
        return 1;
    }

    private $value;
}
