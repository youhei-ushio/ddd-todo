<?php

use package\Application\Service\CreateTaskPageService;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Application\Service\ListTasksService;
use package\Application\Service\ViewTaskService;
use package\Application\Service\ViewTaskValidator;
use package\Domain\Model\ValueObject\TaskSaveDirectory;
use package\Infrastructure\Controller\Http\CreateTaskHandler;
use package\Infrastructure\Controller\Http\CreateTaskPageHandler;
use package\Infrastructure\Controller\Http\ListTasksHandler;
use package\Infrastructure\Controller\Http\ViewTaskHandler;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\HtmlOutputRenderer;
use package\Infrastructure\Presenter\ListTasksHtmlRenderer;
use package\Infrastructure\Presenter\NotFoundRenderer;
use package\Infrastructure\Presenter\PaginatorHtmlBuilder;
use package\Infrastructure\Presenter\ViewTaskHtmlRenderer;
use package\Infrastructure\Service\TaskFileRepository;

require_once 'vendor/autoload.php';

// GETパラメータのactionとhttpメソッドで処理を振り分ける
// 判定しやすいように小文字固定にしておく
$action = strtolower($_GET['action']);
$method = strtolower($_SERVER['REQUEST_METHOD']);

$htmlRenderer = new HtmlOutputRenderer();

if ($action === 'create' && $method === 'get') {
    $controller = new CreateTaskPageHandler();
    $controller->handle(
        new CreateTaskPageService(
            new CreateTaskPageHtmlRenderer($htmlRenderer)
        )
    );
} elseif ($action === 'create' && $method === 'post') {
    $repository = new TaskFileRepository(
        new TaskSaveDirectory()
    );
    $controller = new CreateTaskHandler();
    $controller->handle(
        new CreateTaskService(
            new CreateTaskValidator(
                $repository
            ),
            $repository,
            new CreateTaskHtmlRenderer($htmlRenderer),
            new CreateTaskPageHtmlRenderer($htmlRenderer)
        )
    );
} elseif ($action === 'list' && $method === 'get') {
    $repository = new TaskFileRepository(
        new TaskSaveDirectory()
    );
    $controller = new ListTasksHandler();
    $controller->handle(
        new ListTasksService(
            $repository,
            new ListTasksHtmlRenderer(
                $htmlRenderer,
                new PaginatorHtmlBuilder()
            )
        )
    );
} elseif ($action === 'contents') {
    $repository = new TaskFileRepository(
        new TaskSaveDirectory()
    );
    $controller = new ViewTaskHandler();
    $controller->handle(
        new ViewTaskService(
            new ViewTaskValidator(),
            $repository,
            new ViewTaskHtmlRenderer(
                $htmlRenderer
            ),
            new NotFoundRenderer(
                $htmlRenderer
            )
        )
    );
} else {
    header("HTTP/1.1 404 Not Found");
    echo 'NOT FOUND';
    echo '<br>action=' . $action;
    echo '<br>method=' . $method;
}
