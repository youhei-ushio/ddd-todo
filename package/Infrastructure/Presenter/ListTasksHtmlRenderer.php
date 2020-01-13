<?php

namespace package\Infrastructure\Presenter;

use package\Domain\Model\Entity\Task;
use package\Presentation\ListTasksPresenter;

final class ListTasksHtmlRenderer extends HtmlRenderer implements ListTasksPresenter
{
    /**
     * @param Task[] $tasks
     */
    public function output(array $tasks): void
    {
        $listHtml = '';
        foreach ($tasks as $task) {
            $title = htmlspecialchars($task->title()->value());
            $listHtml .= "<a href=\"/?action=contents&task={$title}\">{$title}</a><br>";
        }

        $html = "
            <html lang=\"ja\">
                <head>
                    <title>タスク一覧</title>
                </head>
                <body>
                    {$listHtml}
                    <p><a href=\"/?action=create\">新規タスク</a></p>
                </body>
            </html>
        ";

        $this->render($html);
    }
}
