<?php
declare(strict_types=1);

namespace Tests\UseCase;

use DOMElement;
use package\Application\Service\LoginRequest;
use package\Application\Service\LoginService;
use package\Application\Service\LoginValidator;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Infrastructure\Presenter\HtmlStreamRenderer;
use package\Infrastructure\Presenter\ListRedirector;
use package\Infrastructure\Presenter\LoginPageHtmlRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Mock\FailureAuthenticator;
use Tests\Mock\HttpHeadersContainer;
use Tests\Mock\LoginRedirectFaker;
use Tests\Mock\SuccessAuthenticator;

final class LoginServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->httpHeaders = new HttpHeadersContainer();
    }

    /**
     * バリデーションエラー：空のアカウント名
     */
    public function testEmptyTitleValidation(): void
    {
        $html = $this->loginFailureResponse('', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('ログイン', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('アカウント名を入力してください。', $errors));
    }

    /**
     * バリデーションエラー：空のパスワード
     */
    public function testEmptyBodyValidation(): void
    {
        $html = $this->loginFailureResponse('', '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('ログイン', $crawler->filter('title')->text());

        $this->assertGreaterThan(0, $crawler->filter('.validation-error')->count());
        $errors = [];
        /** @var DOMElement $element */
        foreach ($crawler->filter('.validation-error') as $element) {
            $errors[] = $element->textContent;
        }
        $this->assertTrue(in_array('パスワードを入力してください。', $errors));
    }

    /**
     * バリデーションエラー後、入力値が復元されている
     */
    public function testRestoreValuesOnValidationError(): void
    {
        $name = str_pad('a', TaskTitle::maxCharacters() + 1);
        $html = $this->loginFailureResponse($name, '');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('ログイン', $crawler->filter('title')->text());

        $outputName = mb_substr($name, 0, mb_strlen($name) - 1); // 長過ぎる入力値は制限されている
        $this->assertEquals($outputName, $crawler->filter('#name')->attr('value'));
    }

    /**
     * ログイン失敗
     */
    public function testLoginFailure(): void
    {
        $title = 'てすと';
        $html = $this->loginFailureResponse($title, 'hoge');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // HTMLタイトル
        $this->assertEquals('ログイン', $crawler->filter('title')->text());

        // エラーメッセージ
        $this->assertEquals('アカウント名、またはパスワードに誤りがあります。', $crawler->filter('.error')->text());

        // 入力値の復元
        $this->assertEquals($title, $crawler->filter('#name')->attr('value'));
    }

    /**
     * ログイン成功
     */
    public function testLogin(): void
    {
        $html = $this->loginSuccessResponse('てすと', 'hogehoge');
        // メモリ上のコンテンツのDOMをクローラで解析する
        $crawler = new Crawler();
        $crawler->addContent($html);

        // 一覧へリダイレクトされること
        $this->assertEquals('Location: http://example.com/?action=list', $this->httpHeaders->get()[0]['header']);
        $this->assertEquals(302, $this->httpHeaders->get()[0]['responseCode']);
    }

    /**
     * ログインが失敗するHTMLレスポンスを得る
     *
     * @param string $name
     * @param string $password
     * @return string
     */
    private function loginFailureResponse(string $name, string $password): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer(
            $stream,
            $this->httpHeaders
        );
        $service = new LoginService(
            new FailureAuthenticator(),
            new LoginValidator(),
            new LoginPageHtmlRenderer($renderer),
            new ListRedirector(
                $this->httpHeaders
            )
        );
        $this->httpHeaders->clear();
        $service->handle(new LoginRequest(
            $name,
            $password
        ));
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);
        return $html;
    }

    /**
     * ログインが成功するHTMLレスポンスを得る
     *
     * @param string $name
     * @param string $password
     * @return string
     */
    private function loginSuccessResponse(string $name, string $password): string
    {
        // サービスの出力先をメモリにする
        $stream = fopen('php://memory', 'r+');
        $renderer = new HtmlStreamRenderer(
            $stream,
            $this->httpHeaders
        );
        $service = new LoginService(
            new SuccessAuthenticator(),
            new LoginValidator(),
            new LoginPageHtmlRenderer($renderer),
            new LoginRedirectFaker(
                $this->httpHeaders
            )
        );
        $service->handle(new LoginRequest(
            $name,
            $password
        ));
        rewind($stream);
        $html = stream_get_contents($stream);
        fclose($stream);
        return $html;
    }

    private $httpHeaders;
}
