<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

interface HtmlRenderer
{
    public function render(string $html): void;
}
