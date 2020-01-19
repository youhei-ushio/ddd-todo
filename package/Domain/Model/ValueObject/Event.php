<?php
declare(strict_types=1);

namespace package\Domain\Model\ValueObject;

class Event
{
    public function __construct()
    {
        $this->occurredOn = EventTimestamp::now();
    }

    public function occurredOn(): EventTimestamp
    {
        return $this->occurredOn;
    }

    private $occurredOn;
}
