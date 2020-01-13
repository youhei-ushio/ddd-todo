<?php

namespace package\Presentation;

use package\Application\Model\ValueObject\ValidationError;

interface CreateTaskPagePresenter
{
    /**
     * @param ValidationError[] $validationErrors
     * @param array $values
     */
    public function render(array $validationErrors, array $values): void;
}
