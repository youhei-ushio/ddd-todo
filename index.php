<?php

use package\Application\Service\CreateTaskPageService;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Application\Service\ListTasksService;
use package\Infrastructure\Controller\Http\CreateTaskHandler;
use package\Infrastructure\Controller\Http\CreateTaskPageHandler;
use package\Infrastructure\Controller\Http\ListTasksHandler;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\ListTasksHtmlRenderer;
use package\Infrastructure\Service\TaskFileRepository;
use package\Infrastructure\Presenter\HtmlOutputRenderer;
use package\Infrastructure\Presenter\PaginatorHtmlBuilder;
use package\Domain\Model\ValueObject\TaskSaveDirectory;

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
} else {
    header("HTTP/1.1 404 Not Found");
    echo 'NOT FOUND';
    echo '<br>action=' . $action;
    echo '<br>method=' . $method;
}
