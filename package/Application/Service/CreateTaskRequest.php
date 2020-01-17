<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;

final class CreateTaskRequest
{
    public function __construct(string $title, string $body)
    {
        $this->data = [
            'title' => $title,
            'body' => $body,
        ];
    }

    public function title(): TaskTitle
    {
        return new TaskTitle($this->data['title']);
    }

    public function body(): TaskBody
    {
        return new TaskBody($this->data['body']);
    }

    public function validate(CreateTaskValidator $validator): array
    {
        return $validator->validate(
            $this->data['title'],
            $this->data['body']
        );
    }

    public function rawValues(): array
    {
        return $this->data;
    }

    private $data;
}
