<?php

namespace package\Application\Service;

use package\Application\Model\ValueObject\ValidationError;
use InvalidArgumentException;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Domain\Service\TaskRepository;

final class CreateTaskValidator
{
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $title
     * @param string $body
     * @return ValidationError[]
     */
    public function validate(string $title, string $body): array
    {
        $errors = [];
        try {
            $title = new TaskTitle($title);
            if ($this->repository->exists($title)) {
                $errors[] = new ValidationError('title', "{$title->value()} は登録済みです。");
            }
        } catch (InvalidArgumentException $exception) {
            $errors[] = new ValidationError('title', $exception->getMessage());
        }

        try {
            new TaskBody($body); // インスタンス化だけ試す
        } catch (InvalidArgumentException $exception) {
            $errors[] = new ValidationError('body', $exception->getMessage());
        }
        return $errors;
    }

    private $repository;
}
