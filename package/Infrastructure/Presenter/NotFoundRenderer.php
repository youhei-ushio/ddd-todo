<?php

namespace package\Infrastructure\Presenter;

use package\Presentation\NotFoundPresenter;

final class NotFoundRenderer implements NotFoundPresenter
{
    public function output(): void
    {
        header("HTTP/1.1 404 Not Found");
    }
}
