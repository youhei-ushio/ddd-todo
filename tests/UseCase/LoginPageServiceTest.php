<?php
declare(strict_types=1);

namespace Tests\UseCase;

use package\Application\Service\LoginPageRequest;
use package\Application\Service\LoginPageService;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use package\Infrastructure\Presenter\LoginPageHtmlRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\HttpHeadersContainer;

final class LoginPageServiceTest extends TestCase
{
    public function testNewPage(): void
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer(
            $stream,
            new HttpHeadersContainer()
        );
        $service = new LoginPageService(
            new LoginPageHtmlRenderer($renderer)
        );
        $service->handle(new LoginPageRequest());
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);

        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('ログイン', $crawler->filter('title')->text());
        // 入力欄
        $this->assertEquals('アカウント名', $crawler->filter('label[for="name"]')->text());
        $this->assertEquals(1, $crawler->filter('#name')->count());
        $this->assertEquals('パスワード', $crawler->filter('label[for="password"]')->text());
        $this->assertEquals(1, $crawler->filter('#password')->count());
        // ボタン
        $this->assertEquals('submit', $crawler->filter('button')->attr('type'));
    }
}
