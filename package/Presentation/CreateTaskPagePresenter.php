<?php
declare(strict_types=1);

namespace package\Presentation;

use package\Application\Model\ValueObject\ValidationError;

interface CreateTaskPagePresenter
{
    /**
     * @param ValidationError[] $validationErrors
     * @param array $values
     */
    public function output(array $validationErrors, array $values): void;
}
