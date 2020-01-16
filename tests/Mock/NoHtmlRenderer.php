<?php

namespace Tests\Mock;

use package\Infrastructure\Presenter\HtmlRenderer;

class NoHtmlRenderer implements HtmlRenderer
{
    public function render(string $html): void
    {
        // 何も出力しない
    }
}
