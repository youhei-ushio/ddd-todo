<?php

namespace Tests;

use package\Application\Service\CreateTaskPageRequest;
use package\Application\Service\CreateTaskPageService;
use package\Infrastructure\Presenter\CreateTaskPageHtmlRenderer;
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
