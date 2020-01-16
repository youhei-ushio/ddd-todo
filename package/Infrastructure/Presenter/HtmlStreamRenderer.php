<?php

namespace package\Infrastructure\Presenter;

use InvalidArgumentException;

final class HtmlStreamRenderer implements HtmlRenderer
{
    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        if (empty($stream)) {
            throw new InvalidArgumentException('');
        }
        $this->stream = $stream;
    }

    public function render(string $html): void
    {
        header('Content-type: text/html');
        @fwrite($this->stream, $html);
    }

    private $stream;
}
