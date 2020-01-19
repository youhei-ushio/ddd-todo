<?php
declare(strict_types=1);

namespace Tests\UseCase;

use package\Application\Service\CreateTaskPageRequest;
use package\Application\Service\CreateTaskPageService;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\HttpHeadersContainer;

final class CreateTaskPageServiceTest extends TestCase
{
    public function testNewPage(): void
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer(
            $stream,
            new HttpHeadersContainer()
        );
        $service = new CreateTaskPageService(
            new CreateTaskPageHtmlRenderer($renderer)
        );
        $service->handle(new CreateTaskPageRequest());
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);

        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('タスク作成', $crawler->filter('title')->text());
        // 入力欄
        $this->assertEquals('タイトル', $crawler->filter('label[for="title"]')->text());
        $this->assertEquals(1, $crawler->filter('#title')->count());
        $this->assertEquals('本文', $crawler->filter('label[for="body"]')->text());
        $this->assertEquals(1, $crawler->filter('#body')->count());
        // ボタン
        $this->assertEquals('submit', $crawler->filter('button')->attr('type'));
        // 一覧へのリンク
        $this->assertEquals('一覧へ戻る', $crawler->filter('a')->text());
        $this->assertEquals('/?action=list', $crawler->filter('a')->attr('href'));
    }
}
