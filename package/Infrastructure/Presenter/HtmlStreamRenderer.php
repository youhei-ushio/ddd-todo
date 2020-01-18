<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use InvalidArgumentException;

final class HtmlStreamRenderer implements HtmlRenderer
{
    /**
     * @param resource $stream
     * @param HttpHeaderWriter $headerWriter
     */
    public function __construct($stream, HttpHeaderWriter $headerWriter)
    {
        if (empty($stream)) {
            throw new InvalidArgumentException('');
        }
        $this->stream = $stream;
        $this->headerWriter = $headerWriter;
    }

    public function render(string $html): void
    {
        $this->headerWriter->output('Content-type: text/html', 200);
        @fwrite($this->stream, $html);
    }

    private $stream;
    private $headerWriter;
}
