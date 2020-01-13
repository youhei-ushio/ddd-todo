<?php

namespace Tests;

use DOMElement;
use package\Application\Service\CreateTaskPageRequest;
use package\Application\Service\CreateTaskPageService;
use package\Application\Service\CreateTaskRequest;
use package\Application\Service\CreateTaskService;
use package\Application\Service\CreateTaskValidator;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Infrastructure\Presenter\CreateTaskHtmlRenderer;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Service\TaskFileRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class CreateTaskPageServiceTest extends TestCase
{
    public function testNewPage()
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $service = new CreateTaskPageService(
            new CreateTaskPageHtmlRenderer($stream)
        );
        $service->handle(new CreateTaskPageRequest());
        rewind($stream);
        $html = stream_get_contents($stream);

        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());
        // 入力欄
        $this->assertEquals(1, $crawler->filter('#title')->count());
        $this->assertEquals(1, $crawler->filter('#body')->count());
        // ボタン
        $this->assertEquals('submit', $crawler->filter('button')->attr('type'));
        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('a')->text());
        $this->assertEquals('/?action=list', $crawler->filter('a')->attr('href'));
    }

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

    public function testIllegalSlashValidation()
    {
        $html = $this->createTaskServiceResponse('abc/test', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('タイトルに / (スラッシュ)は使用できません。', $errors));
    }

    public function testIllegalDotValidation()
    {
        $html = $this->createTaskServiceResponse('abc.test', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('タイトルに . (ドット)は使用できません。', $errors));
    }

    public function testTooLongTitleValidation()
    {
        $maxCharacters = TaskTitle::maxCharacters();
        $html = $this->createTaskServiceResponse(str_pad('a', $maxCharacters + 1), '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array("{$maxCharacters}文字以上のタイトルは許可されません。", $errors));
    }

    public function testEmptyBodyValidation()
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
        $this->assertTrue(in_array('空の本文は許可されません。', $errors));
    }

    public function testTooLongBodyValidation()
    {
        $maxCharacters = TaskBody::maxCharacters();
        $html = $this->createTaskServiceResponse('', str_pad('a', $maxCharacters + 1));
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array("{$maxCharacters}文字以上の本文は許可されません。", $errors));
    }

    private function createTaskServiceResponse(string $title, string $body): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $service = new CreateTaskService(
            new CreateTaskValidator(new TaskFileRepository()),
            new TaskFileRepository(),
            new CreateTaskHtmlRenderer($stream),
            new CreateTaskPageHtmlRenderer($stream)
        );
        $service->handle(new CreateTaskRequest(
            $title,
            $body
        ));
        rewind($stream);
        return stream_get_contents($stream);
    }
}
