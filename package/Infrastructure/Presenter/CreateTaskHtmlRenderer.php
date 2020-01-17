<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Presentation\CreateTaskPresenter;

final class CreateTaskHtmlRenderer implements CreateTaskPresenter
{
    public function __construct(HtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function output(): void
    {
        $html = '
            <html lang="ja">
                <head>
                    <title>タスク作成</title>
                </head>
                <body>
                    <p>タスクを作成しました。</p>
                    <a href="/?action=list">一覧を表示する</a>
                </body>
            </html>
        ';

        $this->renderer->render($html);
    }

    private $renderer;
}
