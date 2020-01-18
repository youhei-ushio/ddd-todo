<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

interface HttpHeaderWriter
{
    public function output(string $header, int $responseCode): void;
}
