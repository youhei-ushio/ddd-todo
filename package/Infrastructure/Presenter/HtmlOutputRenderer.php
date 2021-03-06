<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

final class HtmlOutputRenderer implements HtmlRenderer
{
    public function __construct(HttpHeaderWriter $headerWriter)
    {
        $this->headerWriter = $headerWriter;
    }

    public function render(string $html): void
    {
        $this->headerWriter->output('Content-type: text/html', 200);
        echo $html;
    }

    private $headerWriter;
}
