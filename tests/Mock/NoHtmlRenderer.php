<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Infrastructure\Presenter\HtmlRenderer;

final class NoHtmlRenderer implements HtmlRenderer
{
    public function render(string $html): void
    {
        // 何も出力しない
    }
}
