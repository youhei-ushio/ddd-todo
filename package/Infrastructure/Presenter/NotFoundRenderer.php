<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Presentation\NotFoundPresenter;

final class NotFoundRenderer implements NotFoundPresenter
{
    public function __construct(HtmlRenderer $renderer, HttpHeaderWriter $headerWriter)
    {
        $this->renderer = $renderer;
        $this->headerWriter = $headerWriter;
    }

    public function output(): void
    {
        $html = "
            <html lang=\"ja\">
                <head>
                    <title>タスク詳細</title>
                </head>
                <body>
                    <p class=\"error\">タスクが見つかりません。</p>
                    <a href=\"/?action=list\" class='list'>一覧へ戻る</a>
                </body>
            </html>
        ";

        $this->headerWriter->output('HTTP/1.1 404 Not Found', 404);
        $this->renderer->render($html);
    }

    private $renderer;
    private $headerWriter;
}
