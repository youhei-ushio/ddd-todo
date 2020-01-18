<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Presentation\LogoutPresenter;

final class LogoutHtmlRenderer implements LogoutPresenter
{
    public function __construct(HtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function output(): void
    {
        $html = "
            <html lang=\"ja\">
                <head>
                    <title>ログアウト</title>
                </head>
                <body>
                    <p>ログアウトしました。</p>
                    <a href=\"/?action=login\" class='list'>ログインページ</a>
                </body>
            </html>
        ";

        $this->renderer->render($html);
    }

    private $renderer;
}
