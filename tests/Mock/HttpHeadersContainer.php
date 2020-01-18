<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Infrastructure\Presenter\HttpHeaderWriter;

class HttpHeadersContainer implements HttpHeaderWriter
{
    public function output(string $header, int $responseCode): void
    {
        $this->headers[] = [
            'header' => $header,
            'responseCode' => $responseCode,
        ];
    }

    public function get(): array
    {
        return $this->headers;
    }

    public function clear(): void
    {
        $this->headers = [];
    }

    private $headers = [];
}
