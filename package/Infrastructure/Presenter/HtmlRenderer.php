<?php

namespace package\Infrastructure\Presenter;

use RuntimeException;

final class HtmlRenderer
{
    public function __construct($stream = null)
    {
        if ($stream == null) {
            $this->stream = @fopen('php://output', 'w');
            if ($this->stream === false) {
                throw new RuntimeException('stream open error.');
            }
        } else {
            $this->stream = $stream;
        }
    }

    public function render($html): void
    {
        header('Content-type: text/html');
        @fwrite($this->stream, $html);
    }

    private $stream;
}
