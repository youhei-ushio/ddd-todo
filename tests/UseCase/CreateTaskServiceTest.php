<?php
declare(strict_types=1);

namespace Tests\UseCase;

use DOMElement;
use package\Application\Service\CreateTaskRequest;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use package\Infrastructure\Service\TaskFileRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\TestTaskSaveDirectory;

class CreateTaskServiceTest extends TestCase
{
    /**
     * バリデーションエラー：空のタイトル
     *
     * @runInSeparateProcess
     */
    public function testEmptyTitleValidation()
    {
        $html = $this->createTaskServiceResponse('', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('空のタイトルは許可されません。', $errors));
    }

    /**
     * バリデーションエラー：タイトルにスラッシュを含む
     *
     * @runInSeparateProcess
     */
    public function testIllegalSlashValidation()
    {
        $html = $this->createTaskServiceResponse('abc/test', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('タイトルに / (スラッシュ)は使用できません。', $errors));
    }

    /**
     * バリデーションエラー：タイトルにドットを含む
     *
     * @runInSeparateProcess
     */
    public function testIllegalDotValidation()
    {
        $html = $this->createTaskServiceResponse('abc.test', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('タイトルに . (ドット)は使用できません。', $errors));
    }

    /**
     * バリデーションエラー：タイトルが長すぎる
     *
     * @runInSeparateProcess
     */
    public function testTooLongTitleValidation()
    {
        $maxCharacters = TaskTitle::maxCharacters();
        $html = $this->createTaskServiceResponse(str_pad('a', $maxCharacters + 1), '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array("{$maxCharacters}文字以上のタイトルは許可されません。", $errors));
    }

    /**
     * バリデーションエラー：空の本文
     *
     * @runInSeparateProcess
     */
    public function testEmptyBodyValidation()
    {
        $html = $this->createTaskServiceResponse('', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('空の本文は許可されません。', $errors));
    }


    /**
     * バリデーションエラー：本文が長すぎる
     *
     * @runInSeparateProcess
     */
    public function testTooLongBodyValidation()
    {
        $maxCharacters = TaskBody::maxCharacters();
        $html = $this->createTaskServiceResponse('', str_pad('a', $maxCharacters + 1));
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array("{$maxCharacters}文字以上の本文は許可されません。", $errors));
    }

    /**
     * バリデーションエラー：タイトルの重複
     *
     * @runInSeparateProcess
     */
    public function testDuplicatedTitleValidation()
    {
        $title = '重複確認' . date('Ymdhis');
        $body = "てすと1\nてすと2\nてすと3";
        $this->createTaskServiceResponse($title, $body);

        $html = $this->createTaskServiceResponse($title, $body);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array("{$title} は登録済みです。", $errors));

        // 後始末
        $directory = new TestTaskSaveDirectory();
        $filename = "{$directory->path()}/{$title}.txt";
        unlink($filename);
    }

    /**
     * バリデーションエラー後、入力値が復元されている
     *
     * @runInSeparateProcess
     */
    public function testRestoreValuesOnValidationError()
    {
        $title = str_pad('a', TaskTitle::maxCharacters() + 1);
        $body = str_pad('a', TaskBody::maxCharacters() + 1);
        $html = $this->createTaskServiceResponse($title, $body);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());

        $outputTitle = mb_substr($title, 0, mb_strlen($title) - 1);
        $this->assertEquals($outputTitle, $crawler->filter('#title')->attr('value'));

        $outputBody = mb_substr($body, 0, mb_strlen($body) - 1);
        $this->assertEquals($outputBody, $crawler->filter('#body')->text());
    }

    /**
     * タスク作成成功
     *
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $title = 'てすと' . date('Ymdhis');
        $body = "てすと1\nてすと2\nてすと3";
        $html = $this->createTaskServiceResponse($title, $body);
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());
        // 結果表示
        $this->assertEquals('タスクを作成しました。', $crawler->filter('p')->text());
        $this->assertEquals('一覧を表示する', $crawler->filter('a')->text());
        // リンク
        $this->assertEquals('/?action=list', $crawler->filter('a')->attr('href'));

        // 保存結果
        $directory = new TestTaskSaveDirectory();
        $filename = "{$directory->path()}/{$title}.txt";
        $this->assertTrue(file_exists($filename));
        $this->assertEquals($body, file_get_contents($filename));

        // 後始末
        unlink($filename);
    }

    /**
     * タスク作成サービスを実行してHTMLレスポンスを得る
     *
     * @param string $title
     * @param string $body
     * @return string
     */
    private function createTaskServiceResponse(string $title, string $body): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer($stream);
        $repository = new TaskFileRepository(new TestTaskSaveDirectory());
        $service = new CreateTaskService(
            new CreateTaskValidator($repository),
            $repository,
            new CreateTaskHtmlRenderer($renderer),
            new CreateTaskPageHtmlRenderer($renderer)
        );
        $service->handle(new CreateTaskRequest(
            $title,
            $body
        ));
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);
        return $html;
    }
}
