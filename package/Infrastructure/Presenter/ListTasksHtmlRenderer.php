<?php

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\ValidationError;
use package\Domain\Model\Entity\Task;
use package\Presentation\ListTasksPresenter;

final class ListTasksHtmlRenderer implements ListTasksPresenter
{
    public function __construct(HtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param Task[] $tasks
     * @param ValidationError[] $validationErrors
     */
    public function output(array $tasks, array $validationErrors): void
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

        $this->renderer->render($html);
    }

    private $renderer;
}
