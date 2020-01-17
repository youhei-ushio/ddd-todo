<?php
declare(strict_types=1);

namespace Tests\UseCase;

use package\Application\Service\CreateTaskRequest;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Application\Service\ViewTaskRequest;
use package\Application\Service\ViewTaskService;
use package\Application\Service\ViewTaskValidator;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use package\Infrastructure\Presenter\NotFoundRenderer;
use package\Infrastructure\Presenter\ViewTaskHtmlRenderer;
use package\Infrastructure\Service\TaskFileRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\NoHtmlRenderer;
use Tests\Mock\TestTaskSaveDirectory;

class ViewTaskServiceTest extends TestCase
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
     * 詳細表示
     *
     * @runInSeparateProcess
     */
    public function testList()
    {
        $title = 'タイトルテスト';
        $this->createTask($title, "本文テスト");
        $html = $this->viewTaskServiceResponse($title);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク詳細', $crawler->filter('title')->text());

        // 本文
        $this->assertEquals('本文テスト', $crawler->filter('.contents')->text());

        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('.list')->text());
        $this->assertEquals('/?action=list', $crawler->filter('.list')->attr('href'));
    }

    /**
     * バリデーション：空のタイトル
     *
     * @runInSeparateProcess
     */
    public function testEmptyTitleValidation()
    {
        $title = 'タイトルテスト';
        $this->createTask($title, "本文テスト");
        $html = $this->viewTaskServiceResponse('');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク詳細', $crawler->filter('title')->text());

        // 404エラーページが出ること
        $this->assertEquals('タスクが見つかりません。', $crawler->filter('.error')->text());

        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('.list')->text());
        $this->assertEquals('/?action=list', $crawler->filter('.list')->attr('href'));
    }

    /**
     * バリデーション：長過ぎるタイトル
     *
     * @runInSeparateProcess
     */
    public function testTooLongTitleValidation()
    {
        $title = 'タイトルテスト';
        $this->createTask($title, "本文テスト");
        $html = $this->viewTaskServiceResponse(str_pad('あ', TaskTitle::maxCharacters() + 1));
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク詳細', $crawler->filter('title')->text());

        // 404エラーページが出ること
        $this->assertEquals('タスクが見つかりません。', $crawler->filter('.error')->text());

        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('.list')->text());
        $this->assertEquals('/?action=list', $crawler->filter('.list')->attr('href'));
    }

    /**
     * 存在しない
     *
     * @runInSeparateProcess
     */
    public function testNotFound()
    {
        $title = 'タイトルテスト';
        $this->createTask($title, "本文テスト");
        $html = $this->viewTaskServiceResponse('適当なタイトル');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク詳細', $crawler->filter('title')->text());

        // 404エラーページが出ること
        $this->assertEquals('タスクが見つかりません。', $crawler->filter('.error')->text());

        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('.list')->text());
        $this->assertEquals('/?action=list', $crawler->filter('.list')->attr('href'));
    }

    /**
     * タスク詳細サービスを実行してHTMLレスポンスを得る
     *
     * @param string $title
     * @return string
     */
    private function viewTaskServiceResponse(string $title): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer($stream);
        $repository = new TaskFileRepository(
            new TestTaskSaveDirectory()
        );
        $service = new ViewTaskService(
            new ViewTaskValidator(),
            $repository,
            new ViewTaskHtmlRenderer(
                $renderer
            ),
            new NotFoundRenderer(
                $renderer
            )
        );
        $service->handle(new ViewTaskRequest(
            $title
        ));
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);
        return $html;
    }

    private function createTask(string $title, string $body)
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
