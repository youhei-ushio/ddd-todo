<?php

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\ValidationError;
use package\Presentation\CreateTaskPagePresenter;

final class CreateTaskPageHtmlRenderer extends HtmlRenderer implements CreateTaskPagePresenter
{
    /**
     * @param ValidationError[] $validationErrors
     * @param array $defaultValues
     */
    public function render(array $validationErrors, array $defaultValues): void
    {
        $errors = $this->validationErrorsHtml($validationErrors);

        $values = [];
        if (array_key_exists('title', $defaultValues)) {
            $values['title'] = htmlspecialchars($defaultValues['title']);
        } else {
            $values['title'] = '';
        }
        if (array_key_exists('body', $defaultValues)) {
            $values['body'] = htmlspecialchars($defaultValues['body']);
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
                            <input type=\"text\" name=\"title\" id=\"title\" value=\"{$values['title']}\" maxlength=\"20\">
                            {$errors['title']}
                        </div>
                        <div>
                            <label for=\"body\">本文</label>
                            <textarea name=\"body\" id=\"body\" rows=\"10\" maxlength=\"500\">{$values['body']}</textarea>
                            {$errors['body']}
                        </div>
                        <div>
                            <button type=\"submit\">保存</button>
                        </div>
                    </form>
                    <a href=\"/?action=list\">一覧へ戻る</a>
                </body>
            </html>
        ";

        $this->_render($html);
    }

    /**
     * @param ValidationError[] $validationErrors
     * @return string[]
     */
    private function validationErrorsHtml(array $validationErrors): array
    {
        $errors = [
            'title' => '',
            'body' => '',
        ];
        $titleError = $this->findValidationErrorByFieldName($validationErrors, 'title');
        if ($titleError !== null) {
            $errors['title'] .= '<p class="validation-error">' . $titleError->message() . '</p>';
        }

        $bodyError = $this->findValidationErrorByFieldName($validationErrors, 'body');
        if ($bodyError !== null) {
            $errors['body'] .= '<p class="validation-error">' . $bodyError->message() . '</p>';
        }

        return $errors;
    }

    /**
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
