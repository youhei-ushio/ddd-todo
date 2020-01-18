<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

class PhpHttpHeaderWriter implements HttpHeaderWriter
{
    public function output(string $header, int $responseCode): void
    {
        header($header, $responseCode);
    }
}
