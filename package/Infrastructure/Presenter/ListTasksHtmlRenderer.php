<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;
use package\Domain\Model\Entity\Task;
use package\Presentation\ListTasksPresenter;

final class ListTasksHtmlRenderer implements ListTasksPresenter
{
    public function __construct(HtmlRenderer $renderer, PaginatorHtmlBuilder $paginatorHtmlBuilder)
    {
        $this->renderer = $renderer;
        $this->paginatorHtmlBuilder = $paginatorHtmlBuilder;
    }

    /**
     * @param Task[] $tasks
     * @param RowsPerPage $limit
     * @param PageNumber $currentPage
     * @param PageNumber $maxPage
     */
    public function output(array $tasks, RowsPerPage $limit, PageNumber $currentPage, PageNumber $maxPage): void
    {
        $listHtml = '';
        foreach ($tasks as $task) {
            $title = htmlspecialchars($task->title()->value());
            $listHtml .= "<a href=\"/?action=contents&task={$title}\" class=\"task\">{$title}</a><br>";
        }
        $listHtml .= '<br>';
        $listHtml .= $this->paginatorHtmlBuilder->build($limit, $currentPage, $maxPage);

        $html = "
            <html lang=\"ja\">
                <head>
                    <title>タスク一覧</title>
                </head>
                <body>
                    {$listHtml}
                    <p><a href=\"/?action=create\" class=\"create\">新規タスク</a></p>
                </body>
            </html>
        ";

        $this->renderer->render($html);
    }

    private $renderer;
    private $paginatorHtmlBuilder;
}
