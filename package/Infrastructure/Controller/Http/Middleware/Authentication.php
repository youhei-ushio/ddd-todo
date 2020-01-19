<?php
declare(strict_types=1);

namespace package\Infrastructure\Controller\Http\Middleware;

use package\Application\Service\Authenticator;

final class Authentication
{
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function guard(): void
    {
        // GETパラメータのactionとhttpメソッドで処理を振り分ける
        // 判定しやすいように小文字固定にしておく
        $action = 'list';
        if (array_key_exists('action', $_GET)) {
            $action = strtolower($_GET['action']);
        }
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        if (array_key_exists($action, $this->ignoreActions) && in_array($method, $this->ignoreActions[$action])) {
            // 除外アクション
            return;
        }

        if (!$this->authenticator->isLoggedIn()) {
            header("Location: http://{$_SERVER['HTTP_HOST']}/?action=login");
            exit;
        }
    }

    private $authenticator;
    private $ignoreActions = [
        'login' => ['get', 'post'],
    ];
}
