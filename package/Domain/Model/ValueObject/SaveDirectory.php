<?php
declare(strict_types=1);

namespace package\Domain\Model\ValueObject;

interface SaveDirectory
{
    public function path(): string;
}
