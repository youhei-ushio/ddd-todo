<?php

namespace package\Application\Model\ValueObject;

final class ValidationError
{
    public function __construct(string $fieldName, string $message)
    {
        $this->data = [
            'fieldName' => $fieldName,
            'message' => $message,
        ];
    }

    public function fieldName(): string
    {
        return $this->data['fieldName'];
    }

    public function message(): string
    {
        return $this->data['message'];
    }

    private $data;
}
