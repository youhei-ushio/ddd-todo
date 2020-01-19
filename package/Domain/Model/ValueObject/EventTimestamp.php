<?php
declare(strict_types=1);

namespace package\Domain\Model\ValueObject;

class EventTimestamp
{
    public function __construct(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public static function now(): self
    {
        return new self(time());
    }

    private $timestamp;
}
