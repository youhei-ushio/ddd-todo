<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Presentation\LoginPresenter;

final class ListRedirector implements LoginPresenter
{
    public function __construct(HttpHeaderWriter $headerWriter)
    {
        $this->headerWriter = $headerWriter;
    }

    public function output(): void
    {
        $this->headerWriter->output("Location: http://{$_SERVER['HTTP_HOST']}/?action=list", 302);
    }

    private $headerWriter;
}
