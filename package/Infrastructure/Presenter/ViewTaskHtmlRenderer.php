<?php

namespace package\Infrastructure\Presenter;

use package\Domain\Model\Entity\Task;
use package\Presentation\ViewTaskPresenter;

final class ViewTaskHtmlRenderer implements ViewTaskPresenter
{
    public function __construct(HtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function output(Task $task): void
    {
        $body = htmlspecialchars($task->body()->value());
        $html = "
            <html lang=\"ja\">
                <head>
                    <title>タスク詳細</title>
                </head>
                <body>
                    <pre class='contents'>{$body}</pre>
                    <a href=\"/?action=list\" class='list'>一覧へ戻る</a>
                </body>
            </html>
        ";

        $this->renderer->render($html);
    }

    private $renderer;
}
