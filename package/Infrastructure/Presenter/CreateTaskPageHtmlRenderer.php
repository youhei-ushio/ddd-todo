<?php

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\ValidationError;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Presentation\CreateTaskPagePresenter;

final class CreateTaskPageHtmlRenderer extends HtmlRenderer implements CreateTaskPagePresenter
{
    /**
     * @param ValidationError[] $validationErrors
     * @param array $values
     */
    public function output(array $validationErrors, array $values): void
    {
        $maxTitleLength = TaskTitle::maxCharacters();
        $maxBodyLength = TaskBody::maxCharacters();

        // 入力値の復元
        // エスケープと長さ制限だけしておく
        if (array_key_exists('title', $values)) {
            $values['title'] = mb_substr($values['title'], 0, $maxTitleLength);
            $values['title'] = htmlspecialchars($values['title']);
        } else {
            $values['title'] = '';
        }
        if (array_key_exists('body', $values)) {
            $values['body'] = mb_substr($values['body'], 0, $maxBodyLength);
            $values['body'] = htmlspecialchars($values['body']);
        } else {
            $values['body'] = '';
        }

        $html = "
            <html lang=\"ja\">
                <head>
                    <title>タスク作成</title>
                </head>
                <body>
                    <form action=\"?action=create\" method=\"post\">
                        <div>
                            <label for=\"title\">タイトル</label>
                            <input type=\"text\" name=\"title\" id=\"title\" value=\"{$values['title']}\" maxlength=\"{$maxTitleLength}\">
                            {$this->validationErrorHtml($validationErrors, 'title')}
                        </div>
                        <div>
                            <label for=\"body\">本文</label>
                            <textarea name=\"body\" id=\"body\" rows=\"10\" maxlength=\"{$maxBodyLength}\">{$values['body']}</textarea>
                            {$this->validationErrorHtml($validationErrors, 'body')}
                        </div>
                        <div>
                            <button type=\"submit\">保存</button>
                        </div>
                    </form>
                    <a href=\"/?action=list\">一覧へ戻る</a>
                </body>
            </html>
        ";

        $this->render($html);
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
}
