<?php

namespace package\Application\Service;

use InvalidArgumentException;
use package\Application\Model\ValueObject\ValidationError;
use package\Domain\Model\ValueObject\TaskTitle;

final class ViewTaskValidator
{
    /**
     * @param string $title
     * @return ValidationError[]
     */
    public function validate(string $title): array
    {
        $errors = [];
        try {
            new TaskTitle($title);
        } catch (InvalidArgumentException $exception) {
            $errors[] = new ValidationError('title', $exception->getMessage());
        }
        return $errors;
    }
}
