<?php

namespace package\Domain\Model\Entity;

use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;

final class Task
{
    public function __construct(
        TaskTitle $title,
        TaskBody $body)
    {
        $this->data = [
            'title' => $title,
            'body' => $body,
        ];
    }

    public function title(): TaskTitle
    {
        return $this->data['title'];
    }

    public function setTitle(TaskTitle $value): Task
    {
        $this->data['title'] = $value;
        return $this;
    }

    public function body(): TaskBody
    {
        return $this->data['body'];
    }

    public function setBody(TaskBody $value): Task
    {
        $this->data['body'] = $value;
        return $this;
    }

    private $data;
}
