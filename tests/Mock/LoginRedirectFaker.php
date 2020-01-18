<?php
declare(strict_types=1);

namespace Tests\Mock;

use package\Infrastructure\Presenter\HttpHeaderWriter;
use package\Presentation\LoginPresenter;

final class LoginRedirectFaker implements LoginPresenter
{
    public function __construct(HttpHeaderWriter $headerWriter)
    {
        $this->headerWriter = $headerWriter;
    }

    public function output(): void
    {
        $this->headerWriter->output("Location: http://example.com/?action=list", 302);
    }

    private $headerWriter;
}
