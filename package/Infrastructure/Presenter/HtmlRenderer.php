<?php

namespace package\Infrastructure\Presenter;

interface HtmlRenderer
{
    public function render(string $html): void;
}
