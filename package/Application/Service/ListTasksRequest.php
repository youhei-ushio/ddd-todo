<?php
declare(strict_types=1);

namespace package\Application\Service;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;

final class ListTasksRequest
{
    public function __construct(?int $limit, ?int $page)
    {
        $this->data = [
            'limit' => $limit ?? 10,
            'page' => $page ?? 1,
        ];
    }

    public function limit(): RowsPerPage
    {
        return new RowsPerPage($this->data['limit']);
    }

    public function page(): PageNumber
    {
        return new PageNumber($this->data['page']);
    }

    public function rawValues(): array
    {
        return $this->data;
    }

    private $data;
}
