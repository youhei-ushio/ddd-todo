<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

final class HtmlOutputRenderer implements HtmlRenderer
{
    public function render(string $html): void
    {
        header('Content-type: text/html');
        echo $html;
    }
}
