<?php
declare(strict_types=1);

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\AccountName;
use package\Application\Model\ValueObject\AccountPassword;
use package\Application\Model\ValueObject\ValidationError;
use package\Presentation\LoginPagePresenter;

final class LoginPageHtmlRenderer implements LoginPagePresenter
{
    public function __construct(HtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ValidationError[] $validationErrors
     * @param array $values
     */
    public function output(array $validationErrors, array $values): void
    {
        $maxNameLength = AccountName::maxCharacters();
        $maxPasswordLength = AccountPassword::maxCharacters();

        // 入力値の復元
        // エスケープと長さ制限だけしておく
        if (array_key_exists('name', $values)) {
            $values['name'] = mb_substr($values['name'], 0, $maxNameLength);
            $values['name'] = htmlspecialchars($values['name']);
        } else {
            $values['name'] = '';
        }

        $html = "
            <html lang=\"ja\">
                <head>
                    <title>ログイン</title>
                </head>
                <body>
                    <form action=\"?action=login\" method=\"post\">
                        <div class=\"error\">{$this->validationErrorHtml($validationErrors, 'top')}</div>
                        <div>
                            <label for=\"name\">アカウント名</label>
                            <input type=\"text\" name=\"name\" id=\"name\" value=\"{$values['name']}\" maxlength=\"{$maxNameLength}\">
                            {$this->validationErrorHtml($validationErrors, 'name')}
                        </div>
                        <div>
                            <label for=\"password\">パスワード</label>
                            <input type=\"password\" name=\"password\" id=\"password\" value=\"\" maxlength=\"{$maxPasswordLength}\">
                            {$this->validationErrorHtml($validationErrors, 'password')}
                        </div>
                        <div>
                            <button type=\"submit\">ログイン</button>
                        </div>
                    </form>
                </body>
            </html>
        ";

        $this->renderer->render($html);
    }

    /**
     * バリデーションエラーメッセージ部分のHTMLコンテンツ
     *
     * @param array $validationErrors
     * @param string $fieldName
     * @return string
     */
    private function validationErrorHtml(array $validationErrors, string $fieldName): string
    {
        $error = $this->findValidationErrorByFieldName($validationErrors, $fieldName);
        if ($error === null) {
            return '';
        }
        return '<p class="validation-error">' . $error->message() . '</p>';
    }

    /**
     * 対象フィールドのバリデーションエラーを探す
     *
     * @param ValidationError[] $validationErrors
     * @param string $fieldName
     * @return ValidationError|null
     */
    private function findValidationErrorByFieldName(array $validationErrors, string $fieldName): ?ValidationError
    {
        foreach ($validationErrors as $validationError) {
            if ($validationError->fieldName() === $fieldName) {
                return $validationError;
            }
        }
        return null;
    }

    private $renderer;
}
