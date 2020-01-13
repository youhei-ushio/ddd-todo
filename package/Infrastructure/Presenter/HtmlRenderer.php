<?php

namespace package\Infrastructure\Presenter;

use RuntimeException;

class HtmlRenderer
{
    public function __construct($stream = null)
    {
        if ($stream == null) {
            $this->stream = fopen('php://output', 'w');
            if ($this->stream === false) {
                throw new RuntimeException('stream open error.');
            }
        } else {
            $this->stream = $stream;
        }
    }

    protected function _render($html): void
    {
        fwrite($this->stream, $html);
    }

    private $stream;
}
