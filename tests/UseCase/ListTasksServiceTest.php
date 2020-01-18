<?php
declare(strict_types=1);

namespace Tests\UseCase;

use DOMElement;
use DOMNode;
use package\Application\Service\CreateTaskRequest;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Application\Service\ListTasksRequest;
use package\Application\Service\ListTasksService;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use package\Infrastructure\Presenter\ListTasksHtmlRenderer;
use package\Infrastructure\Presenter\PaginatorHtmlBuilder;
use package\Infrastructure\Service\TaskFileRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\HttpHeadersContainer;
use Tests\Mock\NoHtmlRenderer;
use Tests\Mock\TestTaskSaveDirectory;

class ListTasksServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $directory = new TestTaskSaveDirectory();
        foreach (glob("{$directory->path()}/*.txt") as $filename) {
            unlink($filename);
        }
    }

    /**
     * 一覧表示
     */
    public function testList()
    {
        $totalCount = 10;

        for ($index = 0; $index < $totalCount; $index++) {
            $number = sprintf('%02d', $index + 1);
            $this->createTask("タイトル{$number}", "本文{$number}");
        }
        $html = $this->listTasksServiceResponse(null, null);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク一覧', $crawler->filter('title')->text());

        // 件数
        $this->assertEquals($totalCount, $crawler->filter('.task')->count());

        // 個々のアイテム
        /**
         * @var int $key
         * @var DOMElement $element
         */
        foreach ($crawler->filter('.task') as $key => $element) {
            $number = sprintf('%02d', $key + 1);
            $this->assertEquals("タイトル{$number}", $element->textContent);
            $this->assertEquals("/?action=contents&task=タイトル{$number}", $element->attributes->getNamedItem('href')->textContent);
        }

        // 新規作成へのリンク
        $this->assertEquals('新規タスク', $crawler->filter('.create')->text());
        $this->assertEquals('/?action=create', $crawler->filter('.create')->attr('href'));
        // ログアウトリンク
        $this->assertEquals('ログアウト', $crawler->filter('.logout')->text());
        $this->assertEquals('/?action=logout', $crawler->filter('.logout')->attr('href'));
    }

    /**
     * ページネーション
     */
    public function testPagination()
    {
        $totalCount = 25;
        for ($index = 0; $index < $totalCount; $index++) {
            $number = sprintf('%02d', $index + 1);
            $this->createTask("タイトル{$number}", "本文{$number}");
        }

        $limit = 10;
        $this->_testPagination($limit, 1, $limit, 3, $totalCount, 1, 10);
        $this->_testPagination($limit, 2, $limit, 3, $totalCount, 11, 20);
        $this->_testPagination($limit, 3, 5, 3, $totalCount, 21, 25);
    }

    // 各ページの表示内容
    private function _testPagination(int $limit, int $currentPage, int $count, int $maxPage, int $totalCount, int $first, int $last)
    {
        $html = $this->listTasksServiceResponse($limit, $currentPage);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // アイテム
        $offset = $limit * ($currentPage - 1);
        $tasks = $crawler->filter('.task');
        for ($index = 0; $index < $count; $index++) {
            /** @var DOMNode $element */
            $element = $tasks->getNode($index);
            $number = sprintf('%02d', $index + $offset + 1);
            $this->assertEquals("タイトル{$number}", $element->textContent);
            $this->assertEquals("/?action=contents&task=タイトル{$number}", $element->attributes->getNamedItem('href')->textContent);
        }

        // ページネーション
        $this->assertEquals("全{$totalCount}件", $crawler->filter('.total')->text());
        $this->assertEquals("{$first}~{$last}を表示中", $crawler->filter('.current')->text());

        for ($index = 0; $index < $maxPage; $index++) {
            $page = $index + 1;
            $this->assertEquals($page, $crawler->filter('.page')->getNode($index)->textContent);
            if ($page !== $currentPage) {
                $this->assertEquals('a', $crawler->filter('.page')->getNode($index)->nodeName);
            } else {
                $this->assertEquals('span', $crawler->filter('.page')->getNode($index)->nodeName);
            }
        }
    }

    /**
     * タスク一覧サービスを実行してHTMLレスポンスを得る
     *
     * @param int|null $limit
     * @param int|null $page
     * @return string
     */
    private function listTasksServiceResponse(?int $limit, ?int $page): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer(
            $stream,
            new HttpHeadersContainer()
        );
        $repository = new TaskFileRepository(
            new TestTaskSaveDirectory()
        );
        $service = new ListTasksService(
            $repository,
            new ListTasksHtmlRenderer(
                $renderer,
                new PaginatorHtmlBuilder()
            )
        );
        $service->handle(new ListTasksRequest(
            $limit,
            $page
        ));
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);
        return $html;
    }

    /**
     * タスクを作成し、レスポンスは破棄する
     *
     * @param string $title
     * @param string $body
     */
    private function createTask(string $title, string $body): void
    {
        $repository = new TaskFileRepository(
            new TestTaskSaveDirectory()
        );
        $htmlRenderer = new NoHtmlRenderer();
        $service = new CreateTaskService(
            new CreateTaskValidator(
                $repository
            ),
            $repository,
            new CreateTaskHtmlRenderer(
                $htmlRenderer
            ),
            new CreateTaskPageHtmlRenderer(
                $htmlRenderer
            )
        );
        $service->handle(new CreateTaskRequest($title, $body));
    }
}
