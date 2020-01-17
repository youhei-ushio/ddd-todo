<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Domain\Model\ValueObject\TaskTitle;

final class ViewTaskRequest
{
    public function __construct(?string $title)
    {
        $this->data = [
            'title' => $title,
        ];
    }

    public function title(): TaskTitle
    {
        return new TaskTitle($this->data['title']);
    }

    public function validate(ViewTaskValidator $validator): array
    {
        return $validator->validate(
            $this->data['title']
        );
    }

    public function rawValues(): array
    {
        return $this->data;
    }

    private $data;
}
